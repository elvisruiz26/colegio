// Solicitar permiso para notificaciones
function requestNotificationPermission() {
    if (!("Notification" in window)) {
        console.log("Este navegador no soporta notificaciones");
        return;
    }

    Notification.requestPermission();
}

// Función para mostrar la notificación
function showNotification(docente, fecha) {
    if (Notification.permission === "granted") {
        const notification = new Notification("Falta de Asistencia", {
            body: `El docente ${docente} registró una falta el día ${fecha}`,
            icon: base_url + "/Assets/images/icon.png", 
            requireInteraction: true
        });

        notification.onclick = function() {
            window.open(base_url + "/ListaAsistencias", "_blank");
        };
    }
}

// Función para verificar faltas
function checkFaltas() {
    fetch(base_url + '/ListaAsistencias/getFaltasRecientes')
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            data.forEach(falta => {
                showNotification(falta.nombres + ' ' + falta.apellidos, falta.Fecha);
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

// Al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    requestNotificationPermission();
    
    // Verificar faltas cada 2 minutos
    setInterval(checkFaltas, 10000);
    
    // Primera verificación inmediata
    checkFaltas();
});