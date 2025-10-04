<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - IoT Manager</title>
    {{-- Dependencias de fuentes y Tailwind CSS --}}
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans:wght@400;500;700;900&family=Space+Grotesk:wght@400;500;700">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#101a23]">
    <div class="relative flex min-h-screen w-full flex-col" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
        {{-- ========== HEADER ========== --}}
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#223749] px-4 sm:px-10 py-3 text-white">
            <div class="flex items-center gap-4">
                <div class="size-4">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M44 11.2727C44 14.0109 39.8386 16.3957 33.69 17.6364C39.8386 18.877 44 21.2618 44 24C44 26.7382 39.8386 29.123 33.69 30.3636C39.8386 31.6043 44 33.9891 44 36.7273C44 40.7439 35.0457 44 24 44C12.9543 44 4 40.7439 4 36.7273C4 33.9891 8.16144 31.6043 14.31 30.3636C8.16144 29.123 4 26.7382 4 24C4 21.2618 8.16144 18.877 14.31 17.6364C8.16144 16.3957 4 14.0109 4 11.2727C4 7.25611 12.9543 4 24 4C35.0457 4 44 7.25611 44 11.2727Z" fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Controlhub</h2>
            </div>
            <div class="flex flex-1 justify-end items-center gap-4 sm:gap-8">
                <div class="text-sm text-[#90b0cb] hidden md:block">Bienvenido, <span class="font-bold text-white">{{ $username }}</span>!</div>
                <form method="post" action="{{ route('cerrarSesion') }}">
                    @csrf
                    <button type="submit" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#223749] text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#314d64] transition-colors">
                        <span class="truncate">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- ========== MAIN CONTENT ========== --}}
        <main class="flex flex-1 justify-center p-4 sm:p-5 lg:px-40">
            <div class="w-full max-w-6xl">
                <div class="flex flex-wrap justify-between items-center gap-4 p-4">
                    <h1 class="text-white tracking-light text-3xl font-bold leading-tight">Mis Dispositivos</h1>
                    <a href="{{ route('registroArduino') }}" class="flex cursor-pointer items-center justify-center rounded-lg h-9 px-5 bg-[#2094f3] text-white text-sm font-bold leading-normal hover:bg-[#1a7ad1] transition-colors">
                        <span class="truncate">Añadir Dispositivo</span>
                    </a>
                </div>

                @if($arduinos->count())
                    <div class="grid grid-cols-[repeat(auto-fit,minmax(220px,1fr))] gap-5 p-4">
                        @foreach($arduinos as $arduino)
                            <div class="flex flex-col gap-3 rounded-lg bg-[#18232e] p-4 transition-transform hover:scale-105">
                                {{-- Imagen del dispositivo --}}
                                <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-md" 
                                     style="background-image: url('https://www.cambatronics.com/wp-content/uploads/2021/08/ESP32-WROOM-32-general.jpg');">
                                     {{-- Para imágenes dinámicas, usa: {{ $arduino->image_url ?? 'default_url' }} --}}
                                </div>
                                {{-- Información del dispositivo --}}
                                <div>
                                    <h2 class="text-white text-lg font-bold leading-normal truncate">{{ $arduino->nombre }}</h2>
                                    <p class="text-[#90b0cb] text-sm font-normal leading-normal">IP: {{ $arduino->ip }}</p>
                                </div>
                                {{-- Acciones del dispositivo --}}
                                <div class="mt-2 flex items-center gap-2">
                                    <a href="{{ route('triggers', $arduino->id) }}" class="flex-1 text-center bg-[#2094f3] text-white rounded-md py-2 text-sm font-bold hover:bg-[#1a7ad1] transition-colors">Ir</a>
                                    <button type="button" onclick="openEditArduinoModal({{ $arduino->id }}, '{{ addslashes($arduino->nombre) }}', '{{ addslashes($arduino->ip) }}')" class="flex-1 bg-[#4a5a6a] text-white rounded-md py-2 text-sm font-bold hover:bg-[#5f748a] transition-colors">Editar</button>
                                    <button type="button" onclick="openModal({{ $arduino->id }}, '{{ addslashes($arduino->nombre) }}')" class="flex-1 bg-[#e53e3e] text-white rounded-md py-2 text-sm font-bold hover:bg-[#c53030] transition-colors">Eliminar</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-center">
                        <h2 class="text-xl text-[#90b0cb] mt-10">NO TIENES DISPOSITIVOS VINCULADOS.</h2>
                        <p class="text-[#5f748a] mt-2">Haz clic en "Añadir Dispositivo" para empezar.</p>
                    </div>
                @endif
            </div>
        </main>

        {{-- ========== FOOTER ========== --}}
        <footer class="text-center text-xs text-[#5f748a] py-4 mt-auto border-t border-solid border-[#223749]">
            Proyecto IoT &copy; {{ $year ?? date('Y') }} | Versión 1.0
        </footer>
    </div>

    {{-- ========== MODAL DE ELIMINACIÓN ========== --}}
    <div id="modalEliminar" class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center hidden z-50">
        <div class="bg-[#18232e] p-6 rounded-lg shadow-xl w-full max-w-sm text-center border border-[#223749]">
            <h3 class="text-lg font-bold text-white">Confirmar Eliminación</h3>
            <p id="modalMensaje" class="text-[#90b0cb] my-4"></p>
            <form id="formEliminar" method="post">
                @csrf
                <div class="flex gap-4 mt-6">
                    <button type="button" onclick="closeModal()" class="w-full py-2 bg-[#4a5a6a] text-white rounded-lg font-bold hover:bg-[#5f748a] transition-colors">Cancelar</button>
                    <button type="submit" class="w-full py-2 bg-[#e53e3e] text-white rounded-lg font-bold hover:bg-[#c53030] transition-colors">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== MODAL DE EDICIÓN ========== --}}
    <div id="editArduinoModal" class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center hidden z-50">
        <div class="bg-[#18232e] p-6 rounded-lg shadow-xl w-full max-w-sm border border-[#223749]">
            <h3 class="text-lg font-bold text-white mb-6">Editar Dispositivo</h3>
            <form id="editArduinoForm" method="post">
                @csrf
                <div class="space-y-4">
                    <input type="text" id="editArduinoNombre" name="nombre" placeholder="Nombre del dispositivo" required class="w-full p-3 bg-[#101a23] text-white rounded-lg border border-[#223749] focus:ring-2 focus:ring-[#2094f3] focus:outline-none">
                    <input type="text" id="editArduinoIp" name="ip" placeholder="Dirección IP" required class="w-full p-3 bg-[#101a23] text-white rounded-lg border border-[#223749] focus:ring-2 focus:ring-[#2094f3] focus:outline-none">
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="button" onclick="closeEditArduinoModal()" class="w-full py-2 bg-[#4a5a6a] text-white rounded-lg font-bold hover:bg-[#5f748a] transition-colors">Cancelar</button>
                    <button type="submit" class="w-full py-2 bg-[#2094f3] text-white rounded-lg font-bold hover:bg-[#1a7ad1] transition-colors">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- El mismo JS debería funcionar, ya que mantiene los IDs y las llamadas a funciones --}}
    <script src="{{ asset('js/microcontrolador.js') }}"></script>
</body>
</html>