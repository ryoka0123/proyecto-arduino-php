<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/resetPassword.css') }}">
</head>

<body>
    <div class="container">
        <h2>Cambiar contraseña</h2>
        @if(session('error'))
        <div class="messages">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
        <div class="messages">
            <div>{{ $errors->first() }}</div>
        </div>
        @endif
        <form method="post" action="{{ route('actualizar_password') }}">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
            <input type="password" name="password1" placeholder="Nueva contraseña" required>
            <input type="password" name="password2" placeholder="Confirmar contraseña" required>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>

</html>
