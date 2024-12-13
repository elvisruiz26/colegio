function openModal()
{
    rowTable = "";
    document.querySelector('#idAsistencias').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Cargar Asistencias Docentes";
    document.querySelector("#formAsistencias").reset();
    $('#modalFormAsistencia ').modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('uploadButton').addEventListener('click', subirDatos);
});

function subirDatos() {
  if (!docentesData || Object.keys(docentesData).length === 0) {
    alert("No hay datos para subir.");
    return;
  }

  const stateMapping = JSON.parse(document.getElementById('stateMapping').value);
  const docenteID = document.getElementById('docenteID').value; // Add this line

  fetch(base_url + '/Asistencias/subirDatos', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ docentesData, stateMapping, docenteID }) // Add docenteID to the request body
  })
  .then(response => response.text())
  .then(text => {
    console.log('Response text:', text); // Log the response text for debugging
    try {
      const data = JSON.parse(text);
      if (data.success) {
        alert("Datos subidos exitosamente.");
      } else {
        alert("Error al subir los datos: " + data.error);
      }
    } catch (error) {
      console.error('Error parsing JSON:', error);
      alert("Error al subir los datos.");
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert("Error al subir los datos.");
  });
}