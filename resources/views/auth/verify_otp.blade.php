<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Verificar Código</title>
    <link rel="stylesheet" href="{{ asset('css/verifyOTP.css') }}">
</head>

<body>
    <div class="container">
        <h2>Verifica tu código</h2>
        <div class="messages" style="color:#1976d2; margin-bottom:10px;">
            Si tu correo concuerda con alguno ya registrado, se enviará un código OTP.
        </div>
        @if(session('error'))
            <div class="messages">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="messages">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="post" action="{{ route('verificar_otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
            <input type="text" name="otp" placeholder="Código recibido" required>
            <button type="submit">Verificar</button>
        </form>
        <div class="back-to-login">
            <a href="{{ route('inicioSesion') }}">Volver al login</a>
        </div>
    </div>
</body>

</html>
