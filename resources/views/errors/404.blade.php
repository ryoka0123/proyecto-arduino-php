<!-- templates/404.html -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <link rel="stylesheet" href="{{ asset('css/404.css') }}">
</head>
<body>
    <div class="error-code">404</div>
    <div class="message">¡Ups! La página que buscas no existe.</div>
    <a href="{{ url('/') }}">Volver al inicio</a>
</body>
</html>
