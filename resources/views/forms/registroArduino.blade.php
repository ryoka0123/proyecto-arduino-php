<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Dispositivo</title>
    {{-- Dependencias de fuentes y Tailwind CSS del nuevo diseño --}}
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" as="style" onload="this.rel='stylesheet'" href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans:wght@400;500;700;900&family=Space+Grotesk:wght@400;500;700">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>
<body class="bg-[#101a23]">
    <div class="relative flex min-h-screen w-full flex-col" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
        <div class="layout-container flex h-full grow flex-col">
            {{-- ========== HEADER ========== --}}
            <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#223749] px-4 sm:px-10 py-3">
                <div class="flex items-center gap-4 text-white">
                    <div class="size-4">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M42.1739 20.1739L27.8261 5.82609C29.1366 7.13663 28.3989 10.1876 26.2002 13.7654C24.8538 15.9564 22.9595 18.3449 20.6522 20.6522C18.3449 22.9595 15.9564 24.8538 13.7654 26.2002C10.1876 28.3989 7.13663 29.1366 5.82609 27.8261L20.1739 42.1739C21.4845 43.4845 24.5355 42.7467 28.1133 40.548C30.3042 39.2016 32.6927 37.3073 35 35C37.3073 32.6927 39.2016 30.3042 40.548 28.1133C42.7467 24.5355 43.4845 21.4845 42.1739 20.1739Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">ControlHub</h2>
                </div>
                <div class="flex flex-1 justify-end gap-4 sm:gap-8">
                    {{-- Botón "Volver" del código original, ahora con el estilo nuevo --}}
                    <a href="{{ route('microcontrolador') }}" title="Volver" class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#223749] text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-[#314d64] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                            <path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"></path>
                        </svg>
                    </a>
                    {{-- La imagen de perfil es estática como en el diseño --}}
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://lh3.googleusercontent.com/a/ACg8ocJkY21Bq_VBEJv-GNk_1Mv0dM5S_m3z_f-n_w_g_sXp=s96-c");'></div>
                </div>
            </header>
            
            {{-- ========== FORMULARIO CENTRAL ========== --}}
            <main class="flex flex-1 justify-center items-center p-4 sm:p-5">
                <div class="w-full max-w-md">
                    {{-- Formulario con la lógica del código original --}}
                    <form method="post" action="{{ route('registroArduino') }}">
                        @csrf
                        <div class="layout-content-container flex flex-col w-full py-5">
                            <h2 class="text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight px-4 text-center pb-5">Registra tu Dispositivo</h2>

                            {{-- Bloque de errores del código original, ahora con estilos nuevos --}}
                            @if ($errors->any())
                                <div class="bg-red-500/20 text-red-300 text-sm rounded-lg p-4 mx-4 mb-4 space-y-1 text-center">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Campo "Nombre" --}}
                            <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                                <label class="flex flex-col min-w-40 flex-1">
                                    <p class="text-white text-base font-medium leading-normal pb-2">Nombre del Dispositivo</p>
                                    <input
                                        type="text"
                                        name="nombre"
                                        placeholder="Ej: ESP32 del Salón"
                                        value="{{ old('nombre') }}"
                                        required
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-[#2094f3] border-none bg-[#223749] h-14 placeholder:text-[#90b0cb] p-4 text-base font-normal leading-normal"
                                    />
                                </label>
                            </div>

                            {{-- Campo "IP" --}}
                            <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                                <label class="flex flex-col min-w-40 flex-1">
                                    <p class="text-white text-base font-medium leading-normal pb-2">Dirección IP del Dispositivo</p>
                                    <input
                                        type="text"
                                        name="ip"
                                        placeholder="Ej: 192.168.1.100"
                                        value="{{ old('ip') }}"
                                        required
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-[#2094f3] border-none bg-[#223749] h-14 placeholder:text-[#90b0cb] p-4 text-base font-normal leading-normal"
                                    />
                                </label>
                            </div>

                            {{-- Botón de envío --}}
                            <div class="flex px-4 py-3 mt-4">
                                <button type="submit" class="w-full flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-4 bg-[#2094f3] text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#1a7ad1] transition-colors">
                                    <span class="truncate">Registrar</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
