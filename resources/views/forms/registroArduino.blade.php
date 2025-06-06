<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Arduino</title>
    <link rel="stylesheet" href="{{ asset('css/registroArduino.css') }}">
</head>
<body>
    <div class="form-container">
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
            <a class="back-btn" href="{{ route('microcontrolador') }}" title="Volver" style="position: static; margin-right: 16px;"><span>&#8592;</span></a>
            <h2 style="margin: 0; text-align: center; flex: none;">REGISTRA TU ARDUINO</h2>
        </div>
        @if ($errors->any())
            <div class="messages">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="post">
            @csrf
            <div class="form-fields">
                <input type="text" name="nombre" placeholder="Nombre del Arduino" required>
                <input type="text" name="ip" placeholder="IP del Arduino" required>
            </div>
            <button type="submit" class="arduino-btn">Registrar</button>
        </form>
    </div>
</body>
</html>
