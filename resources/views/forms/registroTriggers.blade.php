
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Trigger</title>
    <link rel="stylesheet" href="{{ asset('css/registroTriggers.css') }}">
</head>
<body>
    <div class="form-box">
        <div style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 10px;">
            <a class="back-btn" href="{{ route('triggers', $arduino_id) }}" style="position: static; margin-right: 16px;">&#8592;</a>
            <h2 style="margin: 0; flex: 1; text-align: left;">REGISTRA TU TRIGGER</h2>
        </div>
        <p>Debes poner el contexto que tienes en el Arduino</p>
        <form method="post">
            @csrf
            <input type="text" name="nombre" placeholder="Nombre del Trigger" required>
            <input type="text" name="contexto" placeholder="Contexto de tu Arduino" required>
            <button type="submit">Registro</button>
        </form>
    </div>
</body>
</html>
