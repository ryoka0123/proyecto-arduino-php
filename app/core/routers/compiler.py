from fastapi import APIRouter
import subprocess, tempfile, os

# Models
from app.core.models.codeModel import CodeModel

router = APIRouter()


@router.post("/compilar")
def esp32_compiler(payload: CodeModel):
    codigo = payload.code
    with tempfile.TemporaryDirectory() as temp_dir:
        sketch_path = os.path.join(temp_dir, "sketch.ino")
        with open(sketch_path, "w") as f:
            f.write(codigo)

        cmd = [
            "arduino-cli", "compile",
            "--fqbn", "esp32:esp32:esp32",
            temp_dir
        ]

        resultado = subprocess.run(cmd, capture_output=True, text=True)

        if resultado.returncode != 0:
            return {"status": "error", "output": resultado.stderr}
        else:
            return {"status": "ok", "output": resultado.stdout}
