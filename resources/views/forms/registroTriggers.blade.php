<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Trigger</title>
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
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0799 24L4 19.2479L9.95537 8.75216L18.04 13.4961L18.0446 4H29.9554L29.96 13.4961L38.0446 8.75216L44 19.2479L35.92 24L44 28.7521L38.0446 39.2479L29.96 34.5039L29.9554 44H18.0446L18.04 34.5039L9.95537 39.2479L4 28.7521L12.0799 24Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]"> ControlHub</h2>
                </div>
                <div class="flex flex-1 justify-end items-center gap-4 sm:gap-8">
                    <a href="{{ route('triggers', $arduino_id) }}" title="Volver" class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#223749] text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover:bg-[#314d64] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                            <path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"></path>
                        </svg>
                    </a>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://lh3.googleusercontent.com/a/ACg8ocJkY21Bq_VBEJv-GNk_1Mv0dM5S_m3z_f-n_w_g_sXp=s96-c");'></div>
                </div>
            </header>
            
            {{-- ========== FORMULARIO CENTRAL ========== --}}
            <main class="flex flex-1 justify-center items-center p-4 sm:p-5">
                <div class="w-full max-w-md">
                    <form method="post">
                        @csrf
                        <div class="layout-content-container flex flex-col w-full py-5">
                            {{-- LÍNEA MODIFICADA: Se añadió text-center para centrar el título y el subtítulo --}}
                            <div class="p-4 text-center">
                                <h2 class="text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">Añadir Nuevo Trigger</h2>
                                @isset($arduino_nombre)
                                <p class="text-[#90b0cb] mt-1">Para el dispositivo: <span class="text-white font-semibold">{{ $arduino_nombre }}</span></p>
                                @endisset
                            </div>
                            
                            @if ($errors->any())
                                <div class="bg-red-500/20 text-red-300 text-sm rounded-lg p-4 mx-4 mb-2 space-y-1 text-center">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                                <label class="flex flex-col min-w-40 flex-1">
                                    <p class="text-white text-base font-medium leading-normal pb-2">Nombre del Trigger</p>
                                    <input type="text" name="nombre" placeholder="Ej: Encender LED del salón" value="{{ old('nombre') }}" required class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-[#2094f3] border-none bg-[#223749] h-14 placeholder:text-[#90b0cb] p-4 text-base font-normal leading-normal" />
                                </label>
                            </div>

                            <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                                <label class="flex flex-col min-w-40 flex-1">
                                    <p class="text-white text-base font-medium leading-normal pb-2">Contexto</p>
                                    <textarea name="contexto" placeholder="Escribe aquí el contexto exacto que tienes en tu código de Arduino" required class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-[#2094f3] border-none bg-[#223749] min-h-36 placeholder:text-[#90b0cb] p-4 text-base font-normal leading-normal">{{ old('contexto') }}</textarea>
                                </label>
                            </div>

                            {{-- LÍNEA MODIFICADA: Se cambió justify-end por justify-center --}}
                            <div class="flex px-4 py-3 justify-center mt-2">
                                <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-[#2094f3] text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#1a7ad1] transition-colors">
                                    <span class="truncate">Registrar Trigger</span>
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