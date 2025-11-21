import subprocess

def run_command_realtime(cmd, on_stdout=None, on_stderr=None):
    process = subprocess.Popen(
        cmd,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        text=True,
        bufsize=1
    )

    for line in process.stdout:
        if on_stdout:
            on_stdout(line.rstrip("\n"))

    for line in process.stderr:
        if on_stderr:
            on_stderr(line.rstrip("\n"))

    process.wait()
    return process.returncode
