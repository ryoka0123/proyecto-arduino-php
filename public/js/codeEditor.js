document.addEventListener('DOMContentLoaded', () => {
    const codeEditor = document.getElementById('code-editor');
    const lineNumbers = document.getElementById('line-numbers');
    const saveBtn = document.getElementById('save-btn');
    const compileBtn = document.getElementById('compile-btn');
    const boardSelector = document.getElementById('board-selector');
    const portSelector = document.getElementById('port-selector');
    const consoleTabBtn = document.getElementById('console-tab-btn');
    const errorTabBtn = document.getElementById('error-tab-btn');
    const consoleOutput = document.getElementById('console-output');
    const errorOutput = document.getElementById('error-output');

    let socket = null;

    function setButtonsLoading(isLoading) {
        const buttons = [saveBtn, compileBtn];
        buttons.forEach(btn => {
            btn.disabled = isLoading;
            btn.classList.toggle('loading', isLoading);
        });
    }

    async function fetchPorts() {
        try {
            const url = `${window.compilerService.httpUrl}/api/compiler/boards`;
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Error del servidor (${response.status})`);

            const data = await response.json();
            portSelector.innerHTML = '';

            if (data.devices && data.devices.length > 0) {
                data.devices.forEach(device => {
                    const option = new Option(`${device.label} (${device.port})`, device.port);
                    portSelector.add(option);
                });
            } else {
                portSelector.add(new Option('No se encontraron puertos', ''));
            }
        } catch (error) {
            console.error("Error fetching ports:", error);
            portSelector.innerHTML = '';
            portSelector.add(new Option('Error al cargar puertos', ''));
        }
    }

    function showTab(tabName) {
        consoleOutput.style.display = (tabName === 'console') ? 'block' : 'none';
        errorOutput.style.display = (tabName === 'error') ? 'block' : 'none';
        consoleTabBtn.classList.toggle('active', tabName === 'console');
        errorTabBtn.classList.toggle('active', tabName === 'error');
    }
    consoleTabBtn.addEventListener('click', () => showTab('console'));
    errorTabBtn.addEventListener('click', () => showTab('error'));

    function compileWithWebSocket() {
        if (socket && socket.readyState === WebSocket.OPEN) return;

        const wsUrl = `${window.compilerService.wsUrl}/api/compiler/ws`;
        socket = new WebSocket(wsUrl);

        socket.onopen = function () {
            const payload = {
                code: codeEditor.value,
                board: boardSelector.value,
                port: portSelector.value,
                filename: "{{ $arduino->nombre }}.ino"
            };
            socket.send(JSON.stringify(payload));
        };

        socket.onmessage = function (event) {
            const message = JSON.parse(event.data);
            const content = (message.data || '').trimEnd() + '\n';
            if (['stderr', 'error'].includes(message.type)) {
                errorOutput.textContent += content;
                showTab('error');
            } else {
                consoleOutput.textContent += content;
            }
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
            errorOutput.scrollTop = errorOutput.scrollHeight;
        };

        socket.onclose = function (event) {
            setButtonsLoading(false);
            if (!event.wasClean) {
                errorOutput.textContent += '> Conexión con el servicio de compilación perdida.\n';
                showTab('error');
            }
            socket = null;
        };

        socket.onerror = function (error) {
            errorOutput.textContent += '> Error de conexión con el servicio de compilación.\n';
            showTab('error');
            setButtonsLoading(false);
            socket = null;
        };
    }

    compileBtn.addEventListener('click', () => {
        consoleOutput.textContent = '> Iniciando compilación...\n';
        errorOutput.textContent = '';
        setButtonsLoading(true);
        showTab('console');
        compileWithWebSocket();
    });

    saveBtn.addEventListener('click', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = saveBtn.dataset.url;
        const code = codeEditor.value;

        setButtonsLoading(true);
        consoleOutput.textContent = '> Guardando...';
        errorOutput.textContent = '';
        showTab('console');

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ code: code })
        })
            .then(response => {
                if (!response.ok) throw new Error(`Error del servidor (${response.status})`);
                return response.json();
            })
            .then(result => {
                consoleOutput.textContent = result.output || '> Guardado con éxito.';
            })
            .catch(error => {
                errorOutput.textContent = '> Error al guardar: ' + error.message;
                showTab('error');
            })
            .finally(() => {
                setButtonsLoading(false);
            });
    });

    function updateLineNumbers() {
        const lineCount = codeEditor.value.split('\n').length;
        lineNumbers.innerHTML = Array.from({ length: lineCount || 1 }, (_, i) => i + 1).join('<br/>');
    }
    codeEditor.addEventListener('scroll', () => { lineNumbers.scrollTop = codeEditor.scrollTop; });
    codeEditor.addEventListener('input', updateLineNumbers);

    fetchPorts();
    updateLineNumbers();
    showTab('console');
});
