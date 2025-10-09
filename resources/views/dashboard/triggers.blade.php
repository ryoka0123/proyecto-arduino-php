<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Triggers de {{ $arduino->nombre }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" as="style" onload="this.rel='stylesheet'"
        href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans:wght@400;500;700;900&family=Space+Grotesk:wght@400;500;700">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script>
            window.ARDUINO = {
                id: {{ $arduino->id }},
                ip: "{{ $arduino->ip }}",
                editar_trigger_url: "{{ route('editar_trigger', [$arduino->id, 0]) }}",
                eliminar_trigger_url: "{{ route('eliminar_trigger', [$arduino->id, 0]) }}"
            };
        </script>
    </head>

<body class="bg-[#101a23]">
    <div class="relative flex min-h-screen w-full flex-col"
        style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
        <div class="layout-container flex h-full grow flex-col">

            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#223749] px-4 sm:px-10 py-3">
                <div class="flex items-center gap-3 text-white">
                    <a href="{{ route('microcontrolador') }}" title="Volver a Dispositivos"
                        class="flex items-center justify-center p-2 rounded-full hover:bg-[#223749] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z">
                            </path>
                        </svg>
                    </a>
                    <a href="{{ route('editor-codigo', $arduino->id) }}" title="Editor"
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
                        <svg viewBox="0 0 48 48" fill="none" class="size-4" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12.0799 24L4 19.2479L9.95537 8.75216L18.04 13.4961L18.0446 4H29.9554L29.96 13.4961L38.0446 8.75216L44 19.2479L35.92 24L44 28.7521L38.0446 39.2479L29.96 34.5039L29.9554 44H18.0446L18.04 34.5039L9.95537 39.2479L4 28.7521L12.0799 24Z"
                                fill="currentColor"></path>
                        </svg>
                        <h1 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">Panel de Control</h1>
                    </div>
                </div>
            </header>

            <main class="flex flex-1 justify-center p-4 sm:p-5 lg:px-40">
                <div class="w-full max-w-6xl">
                    <div class="flex justify-between items-center gap-4 p-4">
                        <div>
                            <h2 class="text-white tracking-light text-3xl font-bold leading-tight">
                                {{ $arduino->nombre }}</h2>
                            <div id="temp-arduino" class="text-[#90b0cb] text-lg mt-2">
                                Temperatura: <span id="valor-temp" class="text-white font-semibold">--</span> °C
                            </div>
                        </div>
                        <a href="{{ route('registroTriggers', $arduino->id) }}"
                            class="flex-shrink-0 cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-[#2094f3] text-white text-sm font-bold leading-normal hover:bg-[#1a7ad1] transition-colors flex">
                            <span>Añadir Trigger</span>
                        </a>
                    </div>

                    <h3 class="text-white text-2xl font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Triggers
                    </h3>

                    @if($triggers->count())
                        <div class="flex flex-wrap gap-5 p-4">
                            @foreach($triggers as $trigger)
                                <div
                                    class="w-full sm:w-[300px] flex flex-col gap-4 rounded-lg border border-[#314f68] bg-[#182834] p-4">
                                    <div class="flex-grow">
                                        <h4 class="text-white text-lg font-bold leading-tight">{{ $trigger->nombre }}</h4>
                                        <p class="text-[#90b0cb] text-sm font-normal leading-normal mt-1 break-all">
                                            {{ $trigger->contexto }}</p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-center gap-2 mt-2">
                                        <button
                                            class="w-full bg-[#2094f3] text-white rounded-md py-2 text-sm font-bold hover:bg-[#1a7ad1] transition-colors"
                                            onclick="accionarTrigger('{{ $arduino->ip }}', '{{ addslashes($trigger->contexto) }}', this)">
                                            Accionar
                                        </button>
                                        <div class="w-full sm:w-auto flex items-center gap-2">
                                            <button
                                                class="w-full sm:w-auto px-4 py-2 bg-[#4a5a6a] text-white rounded-md text-sm font-bold hover:bg-[#5f748a] transition-colors"
                                                onclick="openEditModal({{ $trigger->id }}, {{ json_encode($trigger->nombre) }}, {{ json_encode($trigger->contexto) }})">
                                                Editar
                                            </button>
                                            <button
                                                class="w-full sm:w-auto px-4 py-2 bg-[#e53e3e] text-white rounded-md text-sm font-bold hover:bg-[#c53030] transition-colors"
                                                onclick="openDeleteModal({{ $trigger->id }}, {{ json_encode($trigger->nombre) }})">
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <strong class="text-xl text-[#90b0cb] mt-10">NO TIENES NINGÚN TRIGGER REGISTRADO.</strong>
                        </div>
                    @endif
                </div>
            </main>
        </div>

        {{-- MODALES (El HTML sigue aquí) --}}
        <div id="modalEliminarTrigger"
            class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center hidden z-50">
            <div class="bg-[#182834] p-6 rounded-lg shadow-xl w-full max-w-sm text-center border border-[#223749]">
                <h3 id="modalMensajeTrigger" class="text-lg font-bold text-white"></h3>
                <form id="formEliminarTrigger" method="post" class="mt-6"> @csrf <div class="flex gap-4"><button
                            type="button" onclick="closeDeleteModal()"
                            class="w-full py-2 bg-[#4a5a6a] text-white rounded-lg font-bold hover:bg-[#5f748a] transition-colors">Cancelar</button><button
                            type="submit"
                            class="w-full py-2 bg-[#e53e3e] text-white rounded-lg font-bold hover:bg-[#c53030] transition-colors">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="editModal" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center hidden z-50">
            <div class="bg-[#182834] p-6 rounded-lg shadow-xl w-full max-w-sm border border-[#223749]">
                <h3 class="text-lg font-bold text-white mb-6">Editar Trigger</h3>
                <form id="editForm" method="post"> @csrf <div class="space-y-4"><input type="text" id="editNombre"
                            name="nombre" required
                            class="w-full p-3 bg-[#101a23] text-white rounded-lg border border-[#223749] focus:ring-2 focus:ring-[#2094f3] focus:outline-none"><input
                            type="text" id="editContexto" name="contexto" required
                            class="w-full p-3 bg-[#101a23] text-white rounded-lg border border-[#223749] focus:ring-2 focus:ring-[#2094f3] focus:outline-none">
                    </div>
                    <div class="flex gap-4 mt-6"><button type="button" onclick="closeEditModal()"
                            class="w-full py-2 bg-[#4a5a6a] text-white rounded-lg font-bold hover:bg-[#5f748a] transition-colors">Cancelar</button><button
                            type="submit"
                            class="w-full py-2 bg-[#2094f3] text-white rounded-lg font-bold hover:bg-[#1a7ad1] transition-colors">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>

        <button id="voice-btn" title="Control por voz"
            class="fixed bottom-8 right-8 bg-blue-600 text-white rounded-full size-16 flex items-center justify-center shadow-lg cursor-pointer z-50 hover:bg-blue-500 transition-all duration-300 transform hover:scale-110 animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" width="28px" height="28px" fill="currentColor"
                viewBox="0 0 256 256">
                <path
                    d="M128,176a48.05,48.05,0,0,0,48-48V64a48,48,0,0,0-96,0v64A48.05,48.05,0,0,0,128,176ZM96,64a32,32,0,0,1,64,0v64a32,32,0,0,1-64,0Zm40,143.6V232a8,8,0,0,1-16,0V207.6A80.11,80.11,0,0,1,48,128a8,8,0,0,1,16,0,64,64,0,0,0,128,0,8,8,0,0,1,16,0A80.11,80.11,0,0,1,136,207.6Z">
                </path>
            </svg>
        </button>

        {{-- SCRIPT EXTERNO CARGADO AL FINAL PARA MÁXIMA COMPATIBILIDAD --}}
        <script src="{{ asset('js/triggers.js') }}"></script>
    </div>
</body>

</html>
