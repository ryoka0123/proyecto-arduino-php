// ========================================================================
// || FUNCIONES GLOBALES (Necesarias para los `onclick` del HTML)      ||
// ========================================================================

/**
 * Muestra el modal de edición y rellena sus campos.
 * @param {number} id - El ID del trigger.
 * @param {string} nombre - El nombre actual del trigger.
 * @param {string} contexto - El contexto actual del trigger.
 */
function openEditModal(id, nombre, contexto) {
    const modal = document.getElementById('editModal');
    if (!modal) {
        console.error("El modal de edición no se encontró en la página.");
        return;
    }
    const form = document.getElementById('editForm');
    form.action = window.ARDUINO.editar_trigger_url.replace('/0', '/' + id);
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editContexto').value = contexto;
    modal.classList.remove('hidden');
}

/** Cierra el modal de edición. */
function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) modal.classList.add('hidden');
}

/**
 * Muestra el modal de confirmación para eliminar.
 * @param {number} id - El ID del trigger a eliminar.
 * @param {string} nombre - El nombre del trigger para mostrar en el mensaje.
 */
function openDeleteModal(id, nombre) {
    const modal = document.getElementById('modalEliminarTrigger');
    if (!modal) {
        console.error("El modal de eliminación no se encontró en la página.");
        return;
    }
    const form = document.getElementById('formEliminarTrigger');
    form.action = window.ARDUINO.eliminar_trigger_url.replace('/0', '/' + id);
    document.getElementById('modalMensajeTrigger').innerHTML = `¿Estás seguro de que quieres eliminar "<strong>${nombre}</strong>"?`;
    modal.classList.remove('hidden');
}

/** Cierra el modal de eliminación. */
function closeDeleteModal() {
    const modal = document.getElementById('modalEliminarTrigger');
    if (modal) modal.classList.add('hidden');
}

/**
 * Envía la petición HTTP para accionar un trigger en el dispositivo.
 * @param {string} ip - La IP del dispositivo.
 * @param {string} contexto - El endpoint/contexto a llamar.
 * @param {HTMLButtonElement} btn - El botón que fue presionado.
 */
function accionarTrigger(ip, contexto, btn) {
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = "Accionando...";
    fetch(`http://${ip}/${contexto}`)
        .catch(error => {
            console.error("Error de conexión:", error);
            alert('No se pudo conectar con el dispositivo.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = originalText;
        });
}

// ========================================================================
// || CÓDIGO DE INICIALIZACIÓN (Se ejecuta cuando el DOM está listo)  ||
// ========================================================================

document.addEventListener('DOMContentLoaded', () => {

    // --- INICIALIZACIÓN DE LA ACTUALIZACIÓN DE TEMPERATURA ---
    if (window.ARDUINO && window.ARDUINO.ip) {
        const ipArduino = window.ARDUINO.ip;
        const tempElement = document.getElementById('valor-temp');

        if (tempElement) {
            function actualizarTemperatura() {
                fetch(`http://${ipArduino}/temperatura`)
                    .then(response => response.ok ? response.text() : Promise.reject('Error de respuesta'))
                    .then(temp => tempElement.innerText = temp)
                    .catch(() => tempElement.innerText = '--')
                    .finally(() => setTimeout(actualizarTemperatura, 5000));
            }
            actualizarTemperatura(); // Iniciar el ciclo
        }
    }

    // --- INICIALIZACIÓN DEL CIERRE DE MODALES AL HACER CLIC FUERA ---
    window.addEventListener('click', (event) => {
        if (event.target === document.getElementById('editModal')) closeEditModal();
        if (event.target === document.getElementById('modalEliminarTrigger')) closeDeleteModal();
    });

    // --- INICIALIZACIÓN DEL RECONOCIMIENTO DE VOZ ---
    const voiceBtn = document.getElementById('voice-btn');
    if (voiceBtn && ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.lang = 'es-ES';

        voiceBtn.onclick = () => { recognition.start(); };
        recognition.onstart = () => voiceBtn.classList.add('animate-ping');
        recognition.onend = () => voiceBtn.classList.remove('animate-ping');
        recognition.onerror = (e) => console.error('Error de reconocimiento de voz:', e.error);
        recognition.onresult = (event) => {
            const quitarTildes = s => s.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            let texto = quitarTildes(event.results[0][0].transcript.toLowerCase().trim().replace(/[^\w\s]/g, '').replace(/\s+/g, '_'));
            
            let encontrado = false;
            document.querySelectorAll('.flex.flex-wrap.gap-5.p-4 > div').forEach(card => {
                let nombre = quitarTildes(card.querySelector('h4').innerText.toLowerCase().trim().replace(/[^\w\s]/g, '').replace(/\s+/g, '_'));
                if (texto === nombre) {
                    card.querySelector('button.bg-\\[\\#2094f3\\]').click();
                    encontrado = true;
                }
            });
            if (!encontrado) alert('No se reconoció ningún trigger con ese nombre.');
        };
    } else if (voiceBtn) {
        voiceBtn.style.display = 'none'; // Ocultar si no es compatible
    }
});
