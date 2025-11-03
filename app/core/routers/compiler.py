from fastapi import APIRouter, WebSocket, WebSocketDisconnect
import asyncio
import subprocess
import tempfile
import os

router = APIRouter()

async def stream_process(process: asyncio.subprocess.Process, websocket: WebSocket):
    """Lee stdout y stderr de un proceso y lo transmite a un WebSocket."""
    # asyncio.gather nos permite leer de stdout y stderr simultáneamente
    await asyncio.gather(
        stream_reader(process.stdout, "stdout", websocket),
        stream_reader(process.stderr, "stderr", websocket)
    )

async def stream_reader(stream: asyncio.StreamReader, stream_type: str, websocket: WebSocket):
    """Lee líneas de un stream y las envía como mensajes JSON."""
    while not stream.at_eof():
        line = await stream.readline()
        if line:
            # Enviamos cada línea decodificada al cliente
            await websocket.send_json({"type": stream_type, "data": line.decode('utf-8')})

@router.websocket("/ws")
async def websocket_compiler(websocket: WebSocket):
    await websocket.accept()
    try:
        # 1. Esperar a recibir el código del cliente
        payload = await websocket.receive_json()
        codigo = payload.get("code")

        if not codigo:
            await websocket.send_json({"type": "error", "data": "No se recibió código para compilar."})
            return

        with tempfile.TemporaryDirectory() as temp_dir:
            dir_name = os.path.basename(temp_dir)
            sketch_filename = f"{dir_name}.ino"
            sketch_path = os.path.join(temp_dir, sketch_filename)

            with open(sketch_path, "w", encoding="utf-8") as f:
                f.write(codigo)

            cmd = [
                "arduino-cli", "compile",
                "--fqbn", "esp32:esp32:esp32",
                "--warnings", "all",
                temp_dir
            ]

            # 2. Iniciar el proceso de compilación
            await websocket.send_json({"type": "status", "data": f"Iniciando compilación del sketch..."})
            
            # Usamos asyncio.create_subprocess_exec para no bloquear el servidor
            process = await asyncio.create_subprocess_exec(
                *cmd,
                stdout=subprocess.PIPE,
                stderr=subprocess.PIPE
            )

            # 3. Transmitir la salida en tiempo real
            await stream_process(process, websocket)

            # 4. Esperar a que el proceso termine y enviar el resultado final
            await process.wait()

            if process.returncode == 0:
                await websocket.send_json({"type": "success", "data": "Compilación finalizada con éxito."})
            else:
                await websocket.send_json({"type": "error", "data": f"La compilación falló con el código de salida {process.returncode}."})

    except WebSocketDisconnect:
        print("Cliente desconectado.")
    except Exception as e:
        error_message = f"Ocurrió un error inesperado en el servidor: {str(e)}"
        print(error_message)
        # Intentamos notificar al cliente si la conexión aún está abierta
        try:
            await websocket.send_json({"type": "error", "data": error_message})
        except:
            pass
    finally:
        # Asegurarse de que el WebSocket se cierre
        await websocket.close()
