<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Triggers del Arduino</title>
    <link rel="stylesheet" href="{{ asset('css/triggers.css') }}">
    <script>
        window.ARDUINO = {
            id: {{ $arduino->id }},
            ip: "{{ $arduino->ip }}",
            editar_trigger_url: "{{ route('editar_trigger', [$arduino->id, 0]) }}",
            eliminar_trigger_url: "{{ route('eliminar_trigger', [$arduino->id, 0]) }}"
        };
    </script>
</head>

<body>
    <div class="top-bar">
        <div style="display: flex; align-items: center;">
            <a href="{{ route('microcontrolador') }}" style="text-decoration: none; font-size: 28px; margin-right: 15px;">&#8592;</a>
            <span>{{ $arduino->nombre }}</span>
        </div>
        <form method="get" action="{{ route('registroTriggers', $arduino->id) }}">
            <button class="add-btn" title="Agregar Trigger">+</button>
        </form>
    </div>

    <div class="container">
        <!-- Indicador de temperatura -->
        <div id="temp-arduino" style="text-align:center; font-size:20px; margin-bottom:20px;">
            Temperatura: <span id="valor-temp">--</span> °C
        </div>

        <div class="triggers-container">
            @if($triggers->count())
            @foreach($triggers as $trigger)
            <div class="trigger-card">
                <div class="trigger-title">{{ $trigger->nombre }}</div>
                <div class="trigger-context">{{ $trigger->contexto }}</div>
                <button class="action-btn"
                    onclick="accionarTrigger('{{ $arduino->ip }}', '{{ addslashes($trigger->contexto) }}', this)">
                    Accionar
                </button>
                <div class="trigger-btns">
                    <button class="edit-btn"
                        onclick="openEditModal({{ $trigger->id }}, {{ json_encode($trigger->nombre) }}, {{ json_encode($trigger->contexto) }})">
                        Editar
                    </button>
                    <button class="delete-btn"
                        onclick="openDeleteModal({{ $trigger->id }}, {{ json_encode($trigger->nombre) }})">
                        Eliminar
                    </button>
                </div>
            </div>
            @endforeach
            @else
            <strong>NO TIENES NINGÚN TRIGGER REGISTRADO EN ESTE ARDUINO.</strong>
            @endif
        </div>
    </div>

    <!-- Modal para eliminar trigger -->
    <div id="modalEliminarTrigger" class="modal">
        <div class="modal-content">
            <h3 id="modalMensajeTrigger"></h3>
            <form id="formEliminarTrigger" method="post" style="margin-top:20px;">
                @csrf
                <button type="button" onclick="closeDeleteModal()">Cancelar</button>
                <button type="submit">Confirmar</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar trigger -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Editar Trigger</h3>
            <form id="editForm" method="post" style="margin-top:20px;">
                @csrf
                <input type="text" id="editNombre" name="nombre" required><br>
                <input type="text" id="editContexto" name="contexto" required><br>
                <button type="button" onclick="closeEditModal()">Cancelar</button>
                <button type="submit">Confirmar</button>
            </form>
        </div>
    </div>

    <!-- Botón de micrófono flotante -->
    <button id="voice-btn" title="Control por voz"
        style="position: fixed; bottom: 30px; right: 30px; background: #2196F3; color: #fff; border: none; border-radius: 50%; width: 60px; height: 60px; font-size: 28px; box-shadow: 0 4px 16px rgba(33,150,243,0.18); cursor: pointer; z-index: 2000;">
        🎤
    </button>
    <script src="{{ asset('js/triggers.js') }}"></script>
</body>

</html>