<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código</title>
    {{-- Dependencias de fuentes y Tailwind CSS --}}
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans:wght@400;500;700;900&family=Space+Grotesk:wght@400;500;700">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="relative flex h-auto min-h-screen w-full flex-col bg-[#101a23] overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
        <div class="layout-container flex h-full grow flex-col">
            <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#223749] px-10 py-3">
                <div class="flex items-center gap-4 text-white">
                    <div class="size-4">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">IoT Hub</h2>
                </div>
            </header>

            {{-- Contenedor principal para centrar el formulario --}}
            <div class="flex flex-1 items-center justify-center p-4 sm:p-5">
                
                <div class="layout-content-container flex flex-col w-full max-w-md py-5">
                    <h2 class="text-white tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-3 pt-5">
                        Verificar Código
                    </h2>
                    <p class="text-[#90b0cb] text-base font-normal leading-normal pb-3 pt-1 px-4 text-center">
                        Por favor, ingresa el código OTP enviado a tu correo: <strong class="text-white">{{ $email ?? 'tu correo' }}</strong>
                    </p>

                    {{-- Mensajes de error --}}
                    @if(session('error') || $errors->any())
                        <div class="bg-red-500/20 text-red-300 text-sm rounded-lg p-4 mx-4 my-3 space-y-1 text-center">
                            @if(session('error'))
                                <div>{{ session('error') }}</div>
                            @endif
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Formulario de verificación de OTP --}}
                    <form method="post" action="{{ route('verificar_otp') }}">
                        @csrf
                        {{-- Campo oculto para enviar el email junto con el OTP --}}
                        <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">

                        <div class="flex flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <input name="otp" placeholder="Código OTP" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-[#223749] focus:border-none h-14 placeholder:text-[#90b0cb] p-4 text-base font-normal leading-normal text-center tracking-[0.5em]" required>
                            </label>
                        </div>
                        <div class="flex px-4 py-3">
                            <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 flex-1 bg-[#2094f3] text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#1a7ad1] transition-colors">
                                <span class="truncate">Verificar</span>
                            </button>
                        </div>
                    </form>

                    {{-- Enlace para volver al inicio de sesión --}}
                    <a href="{{ route('inicioSesion') }}" class="text-[#90b0cb] text-sm font-normal leading-normal pb-3 pt-1 px-4 text-center underline hover:text-white transition-colors">
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>