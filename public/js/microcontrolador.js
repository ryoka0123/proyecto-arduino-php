let currentArduinoId = null;

function openModal(arduinoId, nombre) {
    document.getElementById('modalEliminar').style.display = 'flex';
    document.getElementById('modalMensaje').innerText = '¿Estás seguro de eliminar el arduino "' + nombre + '"?';
    document.getElementById('formEliminar').action = '/arduino/' + arduinoId + '/eliminar';
}
function closeModal() {
    document.getElementById('modalEliminar').style.display = 'none';
}
function openEditArduinoModal(id, nombre, ip) {
    currentArduinoId = id;
    document.getElementById('editArduinoNombre').value = nombre;
    document.getElementById('editArduinoIp').value = ip;
    document.getElementById('editArduinoForm').action = '/arduino/' + id + '/editar';
    document.getElementById('editArduinoModal').style.display = 'flex';
}
function closeEditArduinoModal() {
    document.getElementById('editArduinoModal').style.display = 'none';
}

// Cierra el modal si se hace click fuera del contenido
window.onclick = function (event) {
    let editModal = document.getElementById('editModal');
    let deleteModal = document.getElementById('modalEliminar');
    let editArduinoModal = document.getElementById('editArduinoModal');
    if (event.target == editModal) {
        closeEditModal();
    }
    if (event.target == deleteModal) {
        closeModal();
    }
    if (event.target == editArduinoModal) {
        closeEditArduinoModal();
    }
}