from fastapi import APIRouter, WebSocket, WebSocketDisconnect
from concurrent.futures import ThreadPoolExecutor
from dotenv import load_dotenv

# Helper
from app.core.helper.runCommands import run_command_realtime
from app.core.helper.boards import get_detected_ports

import os, asyncio

load_dotenv()
arduino_cli_path = os.getenv("ARDUINO_CLI")
if not arduino_cli_path or not os.path.isfile(arduino_cli_path):
    raise RuntimeError(f"No se encontró arduino-cli en: {arduino_cli_path}")

router = APIRouter()
executor = ThreadPoolExecutor(max_workers=2)

PROJECT_DIR = os.path.dirname(os.path.abspath(__file__))
TEMP_SKETCH_DIR = os.path.join(PROJECT_DIR, "temp_sketches")
os.makedirs(TEMP_SKETCH_DIR, exist_ok=True)


@router.get("/boards")
async def list_boards():
    loop = asyncio.get_running_loop()
    ports = await loop.run_in_executor(executor, get_detected_ports)
    return {"devices": ports}


@router.websocket("/ws")
async def websocket_compiler(websocket: WebSocket):
    await websocket.accept()
    try:
        payload = await websocket.receive_json()
        codigo = payload.get("code")
        port = payload.get("port")
        board = payload.get("board")
        filename = payload.get("filename")

        if not codigo or not port or not board or not filename:
            await websocket.send_json({"type": "error", "data": "Faltan datos: código, puerto, FQBN o nombre de archivo."})
            return

        sketch_dir_name = os.path.splitext(filename)[0]
        sketch_dir = os.path.join(TEMP_SKETCH_DIR, sketch_dir_name)
        os.makedirs(sketch_dir, exist_ok=True)

        sketch_path = os.path.join(sketch_dir, filename)
        with open(sketch_path, "w", encoding="utf-8") as f:
            f.write(codigo)

        await websocket.send_json({"type": "status", "data": "Iniciando compilación..."})

        loop = asyncio.get_running_loop()

        def send_stdout(line):
            asyncio.run_coroutine_threadsafe(
                websocket.send_json({"type": "stdout", "data": line}),
                loop
            )

        def send_stderr(line):
            asyncio.run_coroutine_threadsafe(
                websocket.send_json({"type": "stderr", "data": line}),
                loop
            )

        compile_cmd = [
            arduino_cli_path, "compile",
            "--fqbn", board,
            "--warnings", "none",
            sketch_dir
        ]

        returncode = await loop.run_in_executor(
            executor, run_command_realtime,
            compile_cmd, send_stdout, send_stderr
        )

        if returncode != 0:
            await websocket.send_json({"type": "error", "data": f"La compilación falló ({returncode})."})
            return
        else:
            await websocket.send_json({"type": "success", "data": "Compilación finalizada con éxito."})

        await websocket.send_json({"type": "status", "data": "Iniciando subida..."})

        upload_cmd = [
            arduino_cli_path, "upload",
            "-p", port,
            "--fqbn", board,
            sketch_dir
        ]

        returncode = await loop.run_in_executor(
            executor, run_command_realtime,
            upload_cmd, send_stdout, send_stderr
        )

        if returncode == 0:
            await websocket.send_json({"type": "success", "data": "Subida a la placa finalizada con éxito."})
        else:
            await websocket.send_json({"type": "error", "data": f"La subida falló ({returncode})."})

    except WebSocketDisconnect:
        print("Cliente desconectado.")

    except Exception as e:
        print(f"Error inesperado: {e}")
        try:
            await websocket.send_json({"type": "error", "data": f"Error inesperado: {e}"})
        except:
            pass

    finally:
        await websocket.close()
