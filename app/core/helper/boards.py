import json, os, subprocess
from dotenv import load_dotenv
load_dotenv()

arduino_cli_path = os.getenv("ARDUINO_CLI")
if not arduino_cli_path or not os.path.isfile(arduino_cli_path):
    raise RuntimeError(f"No se encontró arduino-cli en: {arduino_cli_path}")

def get_detected_ports():
    """Devuelve la lista de puertos detectados por arduino-cli"""
    cmd = [arduino_cli_path, "board", "list", "--format", "json"]
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        return []

    try:
        data = json.loads(result.stdout)
        ports = []
        for entry in data.get("detected_ports", []):
            port_info = entry.get("port", {})
            address = port_info.get("address")
            label = port_info.get("label")
            protocol = port_info.get("protocol_label")
            if address:
                ports.append({
                    "port": address,
                    "label": label,
                    "protocol": protocol
                })
        return ports
    except json.JSONDecodeError:
        return []
