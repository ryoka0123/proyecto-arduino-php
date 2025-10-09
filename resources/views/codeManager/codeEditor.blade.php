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
                        "accent-orange": "#F59E0B"
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

        /* Div oculto para calcular la posición del cursor */
        #cursor-position-calculator {
            position: absolute;
            top: -9999px;
            left: -9999px;
            visibility: hidden;
            white-space: pre-wrap; /* Importante para que coincida con el editor */
            word-wrap: break-word;
        }
    </style>
</head>

<body class="bg-background-dark font-display text-text-dark">
    <header
        class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#223749] px-4 sm:px-10 py-3">
        <div class="flex items-center gap-3 text-white">
            <a href="{{ route('triggers', $arduino->id) }}" title="Volver a Triggers"
                class="flex items-center justify-center p-2 rounded-full hover:bg-[#223749] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                    viewBox="0 0 256 256">
                    <path
                        d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z">
                    </path>
                </svg>
            </a>
            <div class="h-6 w-px bg-[#223749]"></div>
            <div class="flex items-center gap-2">
                <h1 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">{{ $arduino->nombre }}.ino</h1>
            </div>
        </div>
    </header>

    <div class="flex flex-col" style="height: calc(100vh - 61px);">
        <main class="flex-grow flex flex-col p-4 overflow-hidden">
            <div class="flex-grow bg-surface-dark rounded-lg shadow-lg overflow-hidden">
                <div class="flex font-mono text-sm leading-6 h-full">
                    <div id="line-numbers" class="text-right pr-4 text-secondary-text-dark select-none bg-gray-800/50 p-4 overflow-hidden">
                        1
                    </div>
                    <div class="flex-grow overflow-hidden">
                        <textarea id="code-editor" class="p-4" spellcheck="false"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-4 bg-surface-dark rounded-lg shadow-lg flex-shrink-0 flex flex-col" style="height: 300px;">
                <div class="border-b border-gray-700">
                    <div class="flex px-2">
                        <button
                            class="px-4 py-2 text-sm font-medium border-b-2 border-accent-blue text-accent-blue">Consola</button>
                        <button
                            class="px-4 py-2 text-sm font-medium text-secondary-text-dark hover:text-text-dark">Errores</button>
                    </div>
                </div>
                <div class="p-4 font-mono text-xs text-secondary-text-dark overflow-y-auto flex-grow">
                    <p>&gt; Iniciando compilación para Arduino UNO...</p>
                    <p>&gt; Compilando sketch...</p>
                    <p>&gt; El sketch usa 928 bytes (2%) del espacio de almacenamiento de programa.</p>
                    <p>&gt; Las variables globales usan 9 bytes (0%) de la memoria dinámica.</p>
                    <p class="text-accent-green">&gt; Compilación finalizada con éxito.</p>
                    <p>&gt; Subiendo sketch a la placa...</p>
                    <p class="text-accent-green">&gt; Upload successful.</p>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Div oculto que se usará para los cálculos -->
    <div id="cursor-position-calculator"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const codeEditor = document.getElementById('code-editor');
            const lineNumbers = document.getElementById('line-numbers');
            const calculator = document.getElementById('cursor-position-calculator');

            const initialCode = `// Inicialización del hardware
void setup() {
    Serial.begin(9600); // Inicia la comunicación serial
    pinMode(LED_BUILTIN, OUTPUT);
}

// Bucle principal del programa
void loop() {
    digitalWrite(LED_BUILTIN, HIGH);
    delay(1000);
    digitalWrite(LED_BUILTIN, LOW);
    delay(1000);
}`;
            
            codeEditor.value = initialCode;

            function updateLineNumbers() {
                if (codeEditor.value === '') {
                    lineNumbers.innerHTML = '1';
                    return;
                }
                const lineCount = codeEditor.value.split('\n').length;
                lineNumbers.innerHTML = Array.from({ length: lineCount }, (_, i) => i + 1).join('<br/>');
            }

            // --- NUEVA FUNCIÓN PARA EL AUTOSCROLL ---
            function scrollCursorIntoView() {
                const styles = window.getComputedStyle(codeEditor);
                [
                    'fontFamily', 'fontSize', 'fontWeight', 'lineHeight', 
                    'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft',
                    'borderTopWidth', 'borderRightWidth', 'borderBottomWidth', 'borderLeftWidth',
                    'width', 'letterSpacing', 'tabSize'
                ].forEach(prop => {
                    calculator.style[prop] = styles[prop];
                });

                const textBeforeCursor = codeEditor.value.substring(0, codeEditor.selectionStart);
                calculator.innerHTML = textBeforeCursor.replace(/\n/g, '<br/>') + '<span></span>';

                const cursorSpan = calculator.querySelector('span');
                const cursorTop = cursorSpan.offsetTop;
                const cursorHeight = cursorSpan.offsetHeight;

                const editorVisibleTop = codeEditor.scrollTop;
                const editorVisibleBottom = editorVisibleTop + codeEditor.clientHeight;

                if (cursorTop < editorVisibleTop) {
                    // El cursor está por encima del área visible, scroll hacia arriba
                    codeEditor.scrollTop = cursorTop;
                } else if (cursorTop + cursorHeight > editorVisibleBottom) {
                    // El cursor está por debajo, scroll hacia abajo
                    codeEditor.scrollTop = cursorTop + cursorHeight - codeEditor.clientHeight;
                }
            }


            codeEditor.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' || e.key === 'Enter') {
                    if (e.key === 'Tab') {
                        e.preventDefault();
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        this.value = this.value.substring(0, start) + "    " + this.value.substring(end);
                        this.selectionStart = this.selectionEnd = start + 4;
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        let lineStart = this.value.lastIndexOf('\n', start - 1) + 1;
                        let currentLine = this.value.substring(lineStart, start);
                        const indentation = currentLine.match(/^\s*/)[0];
                        const textToInsert = '\n' + indentation;
                        this.value = this.value.substring(0, start) + textToInsert + this.value.substring(end);
                        this.selectionStart = this.selectionEnd = start + textToInsert.length;
                    }
                    
                    updateLineNumbers();

                    // Usamos requestAnimationFrame para asegurar que el DOM se actualice
                    // antes de calcular la nueva posición del cursor y hacer scroll.
                    requestAnimationFrame(() => {
                        scrollCursorIntoView();
                        // Sincronizar también los números de línea
                        lineNumbers.scrollTop = codeEditor.scrollTop;
                    });
                }
            });

            // --- NUEVOS EVENTOS PARA EL SCROLL ---
            codeEditor.addEventListener('keyup', (e) => {
                // Se activa con las flechas, Av/Re Pág, Inicio/Fin
                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'PageUp', 'PageDown', 'Home', 'End'].includes(e.key)) {
                    scrollCursorIntoView();
                }
            });
            codeEditor.addEventListener('click', scrollCursorIntoView);
            
            // Sincronización normal del scroll
            codeEditor.addEventListener('scroll', () => {
                lineNumbers.scrollTop = codeEditor.scrollTop;
            });

            codeEditor.addEventListener('input', updateLineNumbers);

            updateLineNumbers();
        });
    </script>
</body>

</html>