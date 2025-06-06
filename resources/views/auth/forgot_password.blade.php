<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/forgotPassword.css') }}">
</head>

<body>
    <div class="container">
        <h2>¿Olvidaste tu contraseña?</h2>
        @if(session('error'))
        <div class="messages">{{ session('error') }}</div>
        @endif
        @if(session('success'))
        <div class="messages" style="color:green;">{{ session('success') }}</div>
        @endif
        <form method="post" action="{{ route('enviar_otp') }}">
            @csrf
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <button type="submit">Enviar código</button>
        </form>
        <div style="margin-top:12px;">
            <a href="{{ route('inicioSesion') }}">Volver al login</a>
        </div>
    </div>
</body>

</html>
