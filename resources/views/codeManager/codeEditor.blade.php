<!DOCTYPE html>
<html class="dark" lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Arduino IDE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;family=Roboto+Mono&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#00335d",
                        "background-light": "#f5f7f8",
                        "background-dark": "rgb(16 26 35 / var(--tw-bg-opacity, 1))",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1a2a38",
                        "text-light": "#1C1C1E",
                        "text-dark": "#E0E0E0",
                        "secondary-text-light": "#6B7280",
                        "secondary-text-dark": "#9CA3AF",
                        "accent-blue": "#3B82F6",
                        "accent-green": "#10B981",
                        "accent-orange": "#F59E0B",
                        "accent-red": "#EF4444" /* Color para errores */
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                        mono: ["Roboto Mono", "monospace"]
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    }
                }
            }
        };
    </script>
    <style>
        .material-icons {
            font-size: 20px;
        }
        #code-editor {
            background-color: transparent;
            border: none;
            outline: none;
            resize: none;
            color: #E0E0E0; 
            font-family: 'Roboto Mono', monospace; 
            width: 100%;
            height: 100%;
            white-space: pre;
            overflow-wrap: normal;
            tab-size: 4;
            -moz-tab-size: 4;
        }
        #line-numbers::-webkit-scrollbar {
            display: none;
        }
        #line-numbers {
            -ms-overflow-style: none; 
            scrollbar-width: none; 
        }
        .text-xs {
            font-size: 15px !important;
            line-height: 1.5rem !important; 
        }
        #cursor-position-calculator {
            position: absolute;
            top: -9999px;
            left: -9999px;
            visibility: hidden;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        /* Estilos para las pestañas de la consola */
        .console-tab {
            border-bottom: 2px solid transparent;
        }
        .console-tab.active {
            color: #3B82F6; /* accent-blue */
            border-bottom-color: #3B82F6; /* accent-blue */
        }
        .console-tab:not(.active) {
            color: #9CA3AF; /* secondary-text-dark */
        }
    </style>
</head>

<body class="bg-background-dark font-display text-text-dark">
    <header
    class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#223749] px-4 sm:px-10 py-3">
    <!-- Contenido Izquierdo (sin cambios) -->
    <div class="flex items-center gap-3 text-white">
        <a href="{{ route('triggers', $arduino->id) }}" title="Volver a Triggers"
            class="flex items-center justify-center p-2 rounded-full hover:bg-[#223749] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"></path></svg>
        </a>
        <div class="h-6 w-px bg-[#223749]"></div>
        <div class="flex items-center gap-2">
            <h1 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">{{ $arduino->nombre }}.ino</h1>
        </div>
    </div>
    
    <!-- Contenido Derecho: Botones Guardar y Compilar -->
    <div class="flex items-center gap-3">
        <!-- BOTÓN AÑADIDO: Guardar -->
        <button id="save-btn" title="Guardar Código"
           class="flex items-center gap-2 h-10 px-4 bg-accent-blue text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                <path d="M208,88H176V48a8,8,0,0,0-8-8H88a8,8,0,0,0-8,8V88H48a8,8,0,0,0-8,8V208a8,8,0,0,0,8,8H208a8,8,0,0,0,8-8V96A8,8,0,0,0,208,88ZM96,56h64V88H96ZM200,200H56V96H80v16a8,8,0,0,0,16,0V96h64v16a8,8,0,0,0,16,0V96h24Z"></path>
            </svg>
            <span>Guardar</span>
        </button>

        <!-- Botón existente: Compilar -->
        <button id="compile-btn" title="Compilar Código"
           class="flex items-center gap-2 h-10 px-4 bg-accent-green text-white text-sm font-bold rounded-lg hover:bg-green-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256"><path d="M240,128a15.87,15.87,0,0,1-7.1,13.31l-160,96A16,16,0,0,1,48,224V32A16,16,0,0,1,72.9,18.69l160,96A15.87,15.87,0,0,1,240,128Zm-16,0L64,32V224l160-96Z"></path></svg>
            <span>Compilar</span>
        </button>
    </div>
</header>

    <div class="flex flex-col" style="height: calc(100vh - 61px);">
        <main class="flex-grow flex flex-col p-4 overflow-hidden">
            <div class="flex-grow bg-surface-dark rounded-lg shadow-lg overflow-hidden">
                <div class="flex font-mono text-sm leading-6 h-full">
                    <div id="line-numbers" class="text-right pr-4 text-secondary-text-dark select-none bg-gray-800/50 p-4 overflow-hidden">1</div>
                    <div class="flex-grow overflow-hidden">
                        <textarea id="code-editor" class="p-4" spellcheck="false">{{ $code }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-4 bg-surface-dark rounded-lg shadow-lg flex-shrink-0 flex flex-col" style="height: 300px;">
                <div class="border-b border-gray-700">
                    <div class="flex px-2">
                        <button id="console-tab-btn" class="console-tab px-4 py-2 text-sm font-medium">Consola</button>
                        <button id="error-tab-btn" class="console-tab px-4 py-2 text-sm font-medium">Errores</button>
                    </div>
                </div>
                
                <!-- Panel de Salida de Consola -->
                <div id="console-output" class="p-4 font-mono text-xs text-secondary-text-dark overflow-y-auto flex-grow whitespace-pre-wrap">
                </div>

                <!-- Panel de Salida de Errores (Oculto por defecto) -->
                <div id="error-output" class="p-4 font-mono text-xs text-accent-red overflow-y-auto flex-grow whitespace-pre-wrap" style="display: none;">
                    {{-- Este contenedor se llenará con el error si existe --}}
                </div>
            </div>
        </main>
    </div>
    
    <form id="compile-form" action="{{ route('compile-code', $arduino->id) }}" method="POST" style="display: none;">
        @csrf
        <textarea name="code" id="hidden-code-input"></textarea>
    </form>

    <form id="save-form" action="{{ route('save-code', $arduino->id) }}" method="POST" style="display: none;">
            @csrf
            <textarea name="code" id="hidden-save-input"></textarea>
        </form>

    <div id="cursor-position-calculator"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Referencias a todos los elementos
            const codeEditor = document.getElementById('code-editor');
            const lineNumbers = document.getElementById('line-numbers');
            const calculator = document.getElementById('cursor-position-calculator');
            const compileBtn = document.getElementById('compile-btn');
            const compileForm = document.getElementById('compile-form');
            const hiddenCodeInput = document.getElementById('hidden-code-input');

            // --- NUEVO: Referencias a elementos de la consola ---
            const consoleTabBtn = document.getElementById('console-tab-btn');
            const errorTabBtn = document.getElementById('error-tab-btn');
            const consoleOutput = document.getElementById('console-output');
            const errorOutput = document.getElementById('error-output');

            // --- NUEVO: Lógica para manejar las pestañas de la consola ---
            function showTab(tabName) {
                if (tabName === 'console') {
                    consoleOutput.style.display = 'block';
                    errorOutput.style.display = 'none';
                    consoleTabBtn.classList.add('active');
                    errorTabBtn.classList.remove('active');
                } else if (tabName === 'error') {
                    consoleOutput.style.display = 'none';
                    errorOutput.style.display = 'block';
                    errorTabBtn.classList.add('active');
                    consoleTabBtn.classList.remove('active');
                }
            }

            consoleTabBtn.addEventListener('click', () => showTab('console'));
            errorTabBtn.addEventListener('click', () => showTab('error'));

            // --- NUEVO: Lógica para manejar la respuesta de la compilación ---
            const compilationError = @json(session('compilation_error'));
            const compilationOutput = @json(session('compilation_output'));

            if (compilationError) {
                // Si hay un error, mostrarlo y cambiar a la pestaña de errores
                errorOutput.textContent = compilationError;
                consoleOutput.textContent = compilationOutput || '> Compilación fallida. Revisa la pestaña de errores.';
                showTab('error');
            } else if (compilationOutput) {
                // Si solo hay salida normal, mostrarla en la consola
                consoleOutput.textContent = compilationOutput;
                errorOutput.textContent = ''; // Limpiar errores previos
                showTab('console');
            } else {
                // Estado inicial si no hay datos de sesión
                showTab('console');
            }


            // (El resto del script para el editor, scroll, etc., sigue igual)
            function updateLineNumbers() { /* ... */ }
            function scrollCursorIntoView() { /* ... */ }
            compileBtn.addEventListener('click', () => { /* ... */ });
            codeEditor.addEventListener('keydown', function(e) { /* ... */ });
            codeEditor.addEventListener('keyup', (e) => { /* ... */ });
            codeEditor.addEventListener('click', scrollCursorIntoView);
            codeEditor.addEventListener('scroll', () => { lineNumbers.scrollTop = codeEditor.scrollTop; });
            codeEditor.addEventListener('input', updateLineNumbers);
            updateLineNumbers();
        });

        // He colapsado las funciones existentes para brevedad, pero su código interno no cambia.
        // Asegúrate de que el código completo de las funciones esté presente en tu archivo final.
        document.addEventListener('DOMContentLoaded', () => {
            const codeEditor = document.getElementById('code-editor');
            const lineNumbers = document.getElementById('line-numbers');
            const calculator = document.getElementById('cursor-position-calculator');
            const compileBtn = document.getElementById('compile-btn');
            const compileForm = document.getElementById('compile-form');
            const hiddenCodeInput = document.getElementById('hidden-code-input');
            const consoleTabBtn = document.getElementById('console-tab-btn');
            const errorTabBtn = document.getElementById('error-tab-btn');
            const consoleOutput = document.getElementById('console-output');
            const errorOutput = document.getElementById('error-output');
            const initialCode = codeEditor.value; // Usar el valor inicial del servidor
            codeEditor.value = initialCode; // No sobrescribir el valor inicial

            function showTab(tabName) {
                if (tabName === 'console') {
                    consoleOutput.style.display = 'block';
                    errorOutput.style.display = 'none';
                    consoleTabBtn.classList.add('active', 'border-accent-blue', 'text-accent-blue');
                    errorTabBtn.classList.remove('active', 'border-accent-blue', 'text-accent-blue');
                } else if (tabName === 'error') {
                    consoleOutput.style.display = 'none';
                    errorOutput.style.display = 'block';
                    errorTabBtn.classList.add('active', 'border-accent-blue', 'text-accent-blue');
                    consoleTabBtn.classList.remove('active', 'border-accent-blue', 'text-accent-blue');
                }
            }
            consoleTabBtn.addEventListener('click', () => showTab('console'));
            errorTabBtn.addEventListener('click', () => showTab('error'));
            const compilationError = @json(session('compilation_error'));
            const compilationOutput = @json(session('compilation_output'));
            if (compilationError) {
                errorOutput.textContent = compilationError;
                consoleOutput.textContent = compilationOutput || '> Compilación fallida. Revisa la pestaña de errores.';
                showTab('error');
            } else if (compilationOutput) {
                consoleOutput.textContent = compilationOutput;
                errorOutput.textContent = '';
                showTab('console');
            } else {
                showTab('console');
            }
            function updateLineNumbers() {
                if (codeEditor.value === '') { lineNumbers.innerHTML = '1'; return; }
                const lineCount = codeEditor.value.split('\n').length;
                lineNumbers.innerHTML = Array.from({ length: lineCount }, (_, i) => i + 1).join('<br/>');
            }
            function scrollCursorIntoView() {
                const styles = window.getComputedStyle(codeEditor);
                ['fontFamily','fontSize','fontWeight','lineHeight','paddingTop','paddingRight','paddingBottom','paddingLeft','borderTopWidth','borderRightWidth','borderBottomWidth','borderLeftWidth','width','letterSpacing','tabSize'].forEach(prop => { calculator.style[prop] = styles[prop]; });
                const textBeforeCursor = codeEditor.value.substring(0, codeEditor.selectionStart);
                calculator.innerHTML = textBeforeCursor.replace(/\n/g, '<br/>') + '<span></span>';
                const cursorSpan = calculator.querySelector('span');
                const cursorTop = cursorSpan.offsetTop;
                const cursorHeight = cursorSpan.offsetHeight;
                const editorVisibleTop = codeEditor.scrollTop;
                const editorVisibleBottom = editorVisibleTop + codeEditor.clientHeight;
                if (cursorTop < editorVisibleTop) { codeEditor.scrollTop = cursorTop; }
                else if (cursorTop + cursorHeight > editorVisibleBottom) { codeEditor.scrollTop = cursorTop + cursorHeight - codeEditor.clientHeight; }
            }
            compileBtn.addEventListener('click', () => {
                hiddenCodeInput.value = codeEditor.value;
                compileForm.submit();
            });
            codeEditor.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' || e.key === 'Enter') {
                    if (e.key === 'Tab') { e.preventDefault(); const start = this.selectionStart, end = this.selectionEnd; this.value = this.value.substring(0, start) + "    " + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 4; }
                    else if (e.key === 'Enter') { e.preventDefault(); const start = this.selectionStart, end = this.selectionEnd; let lineStart = this.value.lastIndexOf('\n', start - 1) + 1; let currentLine = this.value.substring(lineStart, start); const indentation = currentLine.match(/^\s*/)[0]; const textToInsert = '\n' + indentation; this.value = this.value.substring(0, start) + textToInsert + this.value.substring(end); this.selectionStart = this.selectionEnd = start + textToInsert.length; }
                    updateLineNumbers();
                    requestAnimationFrame(() => { scrollCursorIntoView(); lineNumbers.scrollTop = codeEditor.scrollTop; });
                }
            });
            codeEditor.addEventListener('keyup', (e) => { if (['ArrowUp','ArrowDown','ArrowLeft','ArrowRight','PageUp','PageDown','Home','End'].includes(e.key)) { scrollCursorIntoView(); } });
            codeEditor.addEventListener('click', scrollCursorIntoView);
            codeEditor.addEventListener('scroll', () => { lineNumbers.scrollTop = codeEditor.scrollTop; });
            codeEditor.addEventListener('input', updateLineNumbers);
            updateLineNumbers();
        });

        // NUEVO: Lógica para el botón de guardar
        document.addEventListener('DOMContentLoaded', () => {
            const codeEditor = document.getElementById('code-editor');
            const saveBtn = document.getElementById('save-btn');
            const saveForm = document.getElementById('save-form');
            const hiddenSaveInput = document.getElementById('hidden-save-input');

            saveBtn.addEventListener('click', () => {
                hiddenSaveInput.value = codeEditor.value;
                saveForm.submit();
            });
        });
    </script>
</body>

</html>