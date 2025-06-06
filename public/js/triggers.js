let currentTriggerId = null;

function openEditModal(triggerId, nombre, contexto) {
    currentTriggerId = triggerId;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editContexto').value = contexto;
    document.getElementById('editForm').action = window.ARDUINO.editar_trigger_url.replace('/0', '/' + triggerId);
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Modal de eliminar trigger
function openDeleteModal(triggerId, nombre) {
    document.getElementById('modalEliminarTrigger').style.display = 'flex';
    document.getElementById('modalMensajeTrigger').innerText = '¿Estás seguro de eliminar el trigger "' + nombre + '"?';
    document.getElementById('formEliminarTrigger').action = window.ARDUINO.eliminar_trigger_url.replace('/0', '/' + triggerId);
}

function closeDeleteModal() {
    document.getElementById('modalEliminarTrigger').style.display = 'none';
}

function accionarTrigger(ip, contexto, btn) {
    btn.disabled = true;
    btn.innerText = "Accionando...";
    const url = `http://${ip}/${contexto}`;
    fetch(url)
        .then(response => {
            if (response.ok) {
                alert('Acción enviada correctamente.');
            } else {
                alert('Error al accionar el trigger.');
            }
            btn.disabled = false;
            btn.innerText = "Accionar";
        })
        .catch(error => {
            alert('No se pudo conectar con el Arduino.');
            btn.disabled = false;
            btn.innerText = "Accionar";
        });
}

const ipArduino = window.ARDUINO.ip;

function actualizarTemperatura() {
    fetch(`http://${ipArduino}/temperatura`)
        .then(response => response.text())
        .then(temp => {
            document.getElementById('valor-temp').innerText = temp;
            setTimeout(actualizarTemperatura, 5000);
        })
        .catch(() => {
            document.getElementById('valor-temp').innerText = '--';
            setTimeout(actualizarTemperatura, 5000);
        });
}

// Llama una vez al cargar
actualizarTemperatura();

// Cierra los modales si se hace click fuera del contenido
window.onclick = function (event) {
    let editModal = document.getElementById('editModal');
    let deleteModal = document.getElementById('modalEliminarTrigger');
    if (event.target == editModal) {
        closeEditModal();
    }
    if (event.target == deleteModal) {
        closeDeleteModal();
    }
}

// Reconocimiento de voz
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.lang = 'es-ES';
    recognition.continuous = false;
    recognition.interimResults = false;

    document.getElementById('voice-btn').onclick = function () {
        recognition.start();
        this.style.background = '#1769aa';
    };

    recognition.onresult = function (event) {
        function quitarTildes(str) {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        }

        let texto = event.results[0][0].transcript
            .toLowerCase()
            .trim()
            .replace(/[^\w\s]/g, '')
            .replace(/\s+/g, '_');
        texto = quitarTildes(texto);

        console.log('Comando de voz reconocido:', texto);

        const triggers = document.querySelectorAll('.trigger-card');
        let encontrado = false;
        triggers.forEach(card => {
            let nombre = card.querySelector('.trigger-title').innerText
                .toLowerCase()
                .trim()
                .replace(/[^\w\s]/g, '')
                .replace(/\s+/g, '_');
            nombre = quitarTildes(nombre);

            if (texto === nombre) {
                card.querySelector('.action-btn').click();
                encontrado = true;
            }
        });
        if (!encontrado) {
            alert('No se reconoció ningún trigger en el comando de voz.');
        }
    };
    recognition.onerror = function () {
        document.getElementById('voice-btn').style.background = '#2196F3';
        alert('No se pudo reconocer el comando de voz.');
    };
} else {
    document.getElementById('voice-btn').style.display = 'none';
    alert('Tu navegador no soporta reconocimiento de voz.');
}
