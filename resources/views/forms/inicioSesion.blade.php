<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/inicioSesion.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="user-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User">
        </div>
        @if(session('error'))
            <div class="messages">
                <div>{{ session('error') }}</div>
            </div>
        @endif
        <form method="post" action="{{ route('inicioSesion') }}">
            @csrf
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" class="login-btn">INICIAR SESIÓN</button>
        </form>
        <div class="register-link">
            <a href="{{ route('registro') }}">¿No tienes cuenta? Regístrate</a>
            <br>
            <a href="{{ route('recuperar') }}">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</body>
</html>
