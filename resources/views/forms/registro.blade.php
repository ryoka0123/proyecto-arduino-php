<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
</head>

<body>
    <div class="register-container">
        <div class="user-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User">
        </div>
        <h2 style="margin-top: 50px;">REGISTRO</h2>
        @if(session('error'))
        <div class="messages">
            <div>{{ session('error') }}</div>
        </div>
        @endif
        <?php
        if ($errors->any()) {
            echo '<div class="messages">';
            foreach ($errors->all() as $error) {
                echo '<div>' . $error . '</div>';
            }
            echo '</div>';
        }
        ?>
        <form method="post">
            @csrf
            <div class="register-form-fields">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password1" placeholder="Password" required>
                <input type="password" name="password2" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="register-btn">Registrarse</button>
        </form>
        <div class="login-link">
            <a href="{{ route('inicioSesion') }}">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
    <script src="{{ asset('js/registro.js') }}"></script>
</body>

</html>