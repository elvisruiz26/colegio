// Declarar 'docentesData' como variable global
window.docentesData = null; // Variable global para almacenar los datos procesados

// Función para procesar el archivo CSV
function procesarArchivo() {
  const input = document.getElementById('csvFile');
  if (input.files.length === 0) {
    alert("Por favor, selecciona un archivo CSV.");
    return;
  }

  const reader = new FileReader();

  reader.onload = function(event) {
    // Decodificar el contenido utilizando TextDecoder con el encoding adecuado
    const decoder = new TextDecoder('utf-8'); // Cambiar a 'utf-8' para manejar caracteres especiales
    const text = decoder.decode(event.target.result);

    const data = parseCSV(text);
    if (data && Object.keys(data).length > 0) {
      docentesData = data;
      console.log(JSON.stringify(docentesData, null, 2));
      mostrarDatos(data);
    } else {
      alert("No se encontraron datos válidos en el archivo.");
    }
  };

  // Leer el archivo como ArrayBuffer
  reader.readAsArrayBuffer(input.files[0]);
}

// Función para analizar el contenido CSV y transformarlo en un objeto de datos
function parseCSV(text) {
  const lines = text.split('\n');
  const docentes = {};
  let currentDocente = null;
  let currentID = null;

  lines.forEach(line => {
    const columns = line.split(';').map(col => col.trim());

    if (columns[0].startsWith('Nombre')) {
      currentDocente = columns[3]; // Manejar caracteres especiales directamente
      currentID = "";
      docentes[currentDocente] = { id: currentID, fechas: [] };
    } else if (currentDocente && columns[0] && columns[0] !== 'ID') {
      const id = columns[0];
      const date = formatDate(columns[2]);
      const turno = columns[3]; // Manejar caracteres especiales directamente
      const entry = columns[4] || "";
      const exit = columns[5] || "";

      if (date && turno) {
        if (!docentes[currentDocente].id) {
          docentes[currentDocente].id = id;
        }

        // Extraer horas de turno, entrada y salida
        const turnoHoras = turno.match(/\((\d{2}:\d{2})-(\d{2}:\d{2})\)/);
        const turnoInicio = turnoHoras ? turnoHoras[1] : null;
        const turnoFin = turnoHoras ? turnoHoras[2] : null;

        // Determinar el estado de asistencia basado en la hora de entrada
        let estado;
        if (!entry && !exit) {
          estado = 'Faltó';
        } else if (!entry || !exit) {
          estado = 'Observado';
        } else {
          // Calcular la diferencia entre la hora programada y la hora de entrada
          const diferenciaTiempo = calcularDiferenciaTiempo(turnoInicio, entry);
          if (diferenciaTiempo > 0) {
            estado = 'Tardanza';
          } else {
            estado = 'Asistió';
          }
        }

        let observacion = "";
        if (estado === 'Faltó') {
          observacion = "";
        } else if (estado === 'Observado') {
          if (!entry) {
            observacion = "No marcó entrada";
          } else if (!exit) {
            observacion = "No marcó salida";
          }
        }

        // Calcular tiempo total trabajado si no es 'Observado' o 'Faltó'
        let tiempoTotal = '';
        if (estado !== 'Observado' && estado !== 'Faltó') {
          tiempoTotal = calcularTiempo(entry, exit);
        }

        // Calcular diferencia y remover el signo negativo
        let diferenciaAbsoluta = null;
        if (estado !== 'Observado' && estado !== 'Faltó') {
          const diferenciaTiempo = calcularDiferenciaTiempo(turnoInicio, entry);
          diferenciaAbsoluta = Math.abs(diferenciaTiempo);
        }

        docentes[currentDocente].fechas.push({
          fecha: date,
          turno: turno,
          entrada: entry,
          salida: exit,
          tiempoTotal: tiempoTotal !== '' ? formatoHoras(tiempoTotal) : '',
          diferencia: diferenciaAbsoluta !== null ? formatoHoras(diferenciaAbsoluta) : '',
          estado: estado,
          observacion: observacion
        });
      }
    }
  });

  return docentes || {};
}

// Nueva función para calcular la diferencia entre la hora programada y la hora de entrada
function calcularDiferenciaTiempo(programado, real) {
  if (!programado || !real) return null;
  const [horaProg, minutoProg] = programado.split(':').map(Number);
  const [horaReal, minutoReal] = real.split(':').map(Number);
  const totalMinutosProg = horaProg * 60 + minutoProg;
  const totalMinutosReal = horaReal * 60 + minutoReal;
  totalmin = totalMinutosReal - totalMinutosProg;
  if (totalmin < 0) {
    totalmin = 0;
  }
  return totalmin; // Positivo si llegó tarde, negativo o cero si llegó temprano o puntual
}

// Función para formatear la fecha en "YYYY-MM-DD"
function formatDate(dateStr) {
  const [day, month, year] = dateStr.split('/');
  return `${year}-${month}-${day}`;
}

// Función para calcular minutos entre dos tiempos (HH:MM)
function calcularTiempo(inicio, fin) {
  if (!inicio || !fin) return null;
  const [horaInicio, minutoInicio] = inicio.split(':').map(Number);
  const [horaFin, minutoFin] = fin.split(':').map(Number);
  const totalMinutosInicio = horaInicio * 60 + minutoInicio;
  const totalMinutosFin = horaFin * 60 + minutoFin;
  return totalMinutosFin - totalMinutosInicio;
}

// Modificar la función formatoHoras para no incluir el signo negativo
function formatoHoras(minutos) {
  const horas = Math.floor(minutos / 60);
  const mins = minutos % 60;
  const segs = 0; // Asumiendo que no tienes segundos en tus datos
  return `${horas.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;
}

// Función para mostrar los datos procesados en la tabla
function mostrarDatos(docentes) {
  if (!docentes || Object.keys(docentes).length === 0) {
    console.warn("No hay datos para mostrar.");
    return;
  }

  const resultDiv = document.getElementById('result');
  resultDiv.innerHTML = "<tr><th>Docente</th><th>ID</th><th>Fecha</th><th>Turno</th><th>Entrada</th><th>Salida</th><th>Horas Trabajadas</th><th>Tarde/Temprano</th><th>Estado</th><th>Observación</th></tr>";

  for (const [nombre, info] of Object.entries(docentes)) {
    info.fechas.forEach(fechaInfo => {  
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${nombre}</td>
        <td>${info.id}</td>
        <td>${fechaInfo.fecha}</td>
        <td>${fechaInfo.turno}</td>
        <td>${fechaInfo.entrada}</td>
        <td>${fechaInfo.salida}</td>
        <td>${fechaInfo.tiempoTotal}</td>
        <td>${fechaInfo.diferencia}</td>
        <td>${fechaInfo.estado}</td>
        <td>${fechaInfo.observacion}</td>
      `;
      resultDiv.appendChild(row);
    });
  }
}

// Evento para el botón "Procesar Archivo"
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('csvFile').addEventListener('change', procesarArchivo);
});

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
      'Content-Type': 'application/json; charset=UTF-8' // Ensure UTF-8 encoding
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