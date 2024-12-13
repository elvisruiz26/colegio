function procesarArchivo() {
    const input = document.getElementById('csvFile');
    const reader = new FileReader();
  
    reader.onload = function(event) {
      const text = event.target.result;
      const data = parseCSV(text);
      mostrarDatos(data);
    };
  
    reader.readAsText(input.files[0]);
  }
  
  function parseCSV(text) {
    const lines = text.split('\n');
    const docentes = {};
    let currentDocente = null;
    let currentID = null;
  
    lines.forEach(line => {
      const columns = line.split(';');
      if (columns[0].startsWith('Nombre')) {
        currentDocente = columns[3].trim();
        currentID = "";
        docentes[currentDocente] = { id: currentID, fechas: [] };
      } else if (currentDocente && columns[0] && columns[0] !== 'ID') {
        const id = columns[0].trim();
        const date = columns[2].trim();
        const turno = columns[3].trim();
        const entry = columns[4].trim() || "";
        const exit = columns[5].trim() || "";
  
        if (date && turno) {
          if (!docentes[currentDocente].id) {
            docentes[currentDocente].id = id;
          }
          const turnoHoras = turno.match(/\((\d{2}:\d{2})-(\d{2}:\d{2})\)/);
          const turnoInicio = turnoHoras ? turnoHoras[1] : null;
          const turnoFin = turnoHoras ? turnoHoras[2] : null;
          const totalTiempo = calcularTiempo(entry, exit);
          const turnoTiempo = calcularTiempo(turnoInicio, turnoFin);
          const diferenciaTiempo = totalTiempo && turnoTiempo ? totalTiempo - turnoTiempo : null;
  
          let estado;
          if (!entry && !exit) {
            estado = 'Falta';
          } else if (!entry || !exit) {
            estado = 'Observado';
          } else if (diferenciaTiempo < 0) {
            estado = 'Tardanza';
          } else {
            estado = 'Asistencia';
          }
  
          docentes[currentDocente].fechas.push({
            fecha: date,
            turno: turno,
            entrada: entry,
            salida: exit,
            tiempoTotal: totalTiempo ? formatoHoras(totalTiempo) : '',
            diferencia: diferenciaTiempo ? formatoHoras(diferenciaTiempo) : '',
            estado: estado
          });
        }
      }
    });
    return docentes;
  }
  
  function registrarAsistencias() {
    const jsonData = [];
    for (const [nombre, info] of Object.entries(docentes)) {
      info.fechas.forEach(fechaInfo => {
        const estado = convertirEstado(fechaInfo.estado);
        jsonData.push({
          nombre: nombre,
          id: info.id,
          fecha: fechaInfo.fecha,
          entrada: fechaInfo.entrada,
          salida: fechaInfo.salida,
          estado: estado
        });
      });
    }
  
    fetch('index.php?action=registrar_asistencias', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Asistencias registradas exitosamente');
      } else {
        alert('Error al registrar asistencias: ' + data.error);
      }
    })
    .catch(error => console.error('Error:', error));
  }
  
  function convertirEstado(estadoTexto) {
    switch (estadoTexto) {
      case 'Asistencia': return 1;
      case 'Tardanza': return 2;
      case 'Falta': return 3;
      case 'Observado': return 4;
      default: return null;
    }
  }
  