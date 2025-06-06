<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/microcontrolador.css') }}">
</head>
<body>
    <div class="navbar">
        <div class="bienvenido">Bienvenido, {{ $username }}!</div>
        <div class="acciones">
            <form method="get" action="{{ route('registroArduino') }}" style="display:inline;">
                <button class="add-btn" title="Agregar Arduino">+</button>
            </form>
            <form method="post" action="{{ route('cerrarSesion') }}" style="display:inline;">
                @csrf
                <button type="submit" class="cerrar-btn">Cerrar sesión</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if($arduinos->count())
            @foreach($arduinos as $arduino)
                <div class="arduino-card">
                    <div class="arduino-info-rect">
                        <h2 style="margin:0 0 8px 0;">{{ $arduino->nombre }}</h2>
                        <p style="margin:0;">IP: {{ $arduino->ip }}</p>
                    </div>
                    <div class="arduino-actions">
                        <a href="{{ route('triggers', $arduino->id) }}">Ir</a>
                        <button type="button" class="edit-btn"
                            onclick="openEditArduinoModal({{ $arduino->id }}, '{{ addslashes($arduino->nombre) }}', '{{ addslashes($arduino->ip) }}')">
                            Editar
                        </button>
                        <button type="button" class="delete-btn"
                            onclick="openModal({{ $arduino->id }}, '{{ addslashes($arduino->nombre) }}')">
                            Eliminar
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <h2>NO TIENES ARDUINOS VINCULADOS.</h2>
        @endif
    </div>

    <!-- Modal -->
    <div id="modalEliminar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); justify-content:center; align-items:center;">
        <div style="background:white; padding:30px; border-radius:15px; min-width:300px; text-align:center;">
            <h3 id="modalMensaje"></h3>
            <form id="formEliminar" method="post" style="margin-top:20px;">
                @csrf
                <button type="button" onclick="closeModal()">Cancelar</button>
                <button type="submit">Confirmar</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar Arduino -->
    <div id="editArduinoModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); justify-content:center; align-items:center; z-index:2000;">
        <div style="background:white; padding:30px; border-radius:15px; min-width:240px; text-align:center;">
            <h3>Editar Arduino</h3>
            <form id="editArduinoForm" method="post" style="margin-top:20px;">
                @csrf
                <input type="text" id="editArduinoNombre" name="nombre" placeholder="Nombre" required style="width:90%;padding:10px;margin-bottom:15px;border-radius:6px;border:1.5px solid #2196F3;">
                <input type="text" id="editArduinoIp" name="ip" placeholder="IP" required style="width:90%;padding:10px;margin-bottom:15px;border-radius:6px;border:1.5px solid #2196F3;">
                <button type="button" onclick="closeEditArduinoModal()">Cancelar</button>
                <button type="submit" style="background:linear-gradient(135deg,#4CAF50 60%,#8bc34a 100%);color:#fff;">Confirmar</button>
            </form>
        </div>
    </div>

    <div class="footer">
        Proyecto Arduino &copy; {{ $year ?? '2025' }} | version 1.0
    </div>
    <script src="{{ asset('js/microcontrolador.js') }}"></script>
</body>
</html>
