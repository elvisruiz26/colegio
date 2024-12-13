function exportarExcel(docentes) {
  const workbook = new ExcelJS.Workbook();

  // Crear la hoja con todas las asistencias
  const allSheet = workbook.addWorksheet('Todos los docentes');
  allSheet.columns = [
    { header: 'Docente', key: 'docente', width: 25 },
    { header: 'ID', key: 'id', width: 15 },
    { header: 'Fecha', key: 'fecha', width: 15 },
    { header: 'Turno', key: 'turno', width: 30 },
    { header: 'Entrada', key: 'entrada', width: 15 },
    { header: 'Salida', key: 'salida', width: 15 },
    { header: 'Horas Trabajadas', key: 'tiempoTotal', width: 20 },
    { header: 'Tardanzas', key: 'diferencia', width: 20 },
    { header: 'Estado', key: 'estado', width: 15 },
    { header: 'Observación', key: 'observacion', width: 30 },
  ];

  let currentRow = 1; // Start at the first row
  let totalAsistencias = 0; // Add this line
  let totalFaltas = 0; // Add this line
  let totalTardanzas = 0; // Add this line
  let totalObservaciones = 0; // Add this line

  // Añadir las filas de datos de todos los docentes
  for (const [nombre, info] of Object.entries(docentes)) {
    // Añadir el nombre del docente como título
    allSheet.mergeCells(`A${currentRow}:J${currentRow}`);
    const titleRow = allSheet.getRow(currentRow);
    titleRow.getCell(1).value = nombre;
    titleRow.getCell(1).font = { bold: true, size: 14, color: { argb: 'FF000000' } };
    titleRow.getCell(1).alignment = { horizontal: 'center', vertical: 'middle' };
    currentRow++;

    // Añadir cabeceras para cada tabla individual
    const headerRow = allSheet.addRow({
      docente: 'Docente',
      id: 'ID',
      fecha: 'Fecha',
      turno: 'Turno',
      entrada: 'Entrada',
      salida: 'Salida',
      tiempoTotal: 'Horas Trabajadas',
      diferencia: 'Tardanzas',
      estado: 'Estado',
      observacion: 'Observación'
    });
    headerRow.eachCell(cell => {
      cell.font = { bold: true, size: 14, color: { argb: 'FF000000' } };
      cell.fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'FFADD8E6' } // Color celeste
      };
      cell.alignment = { horizontal: 'center', vertical: 'middle' };
      cell.border = {
        top: { style: 'thin', color: { argb: 'FF000000' } },
        left: { style: 'thin', color: { argb: 'FF000000' } },
        bottom: { style: 'thin', color: { argb: 'FF000000' } },
        right: { style: 'thin', color: { argb: 'FF000000' } }
      };
    });
    currentRow++;

    info.fechas.forEach(fechaInfo => {
      const tiempoTotalOriginal = fechaInfo.tiempoTotal || '';
      const diferenciaOriginal = fechaInfo.diferencia || '';
      const estado = fechaInfo.estado;

      // Determinar si llegó tarde
      const esTardanza = estado === 'Tardanza';

      const row = allSheet.addRow({
        docente: nombre,
        id: info.id,
        fecha: fechaInfo.fecha,
        turno: fechaInfo.turno,
        entrada: fechaInfo.entrada,
        salida: fechaInfo.salida,
        tiempoTotal: tiempoTotalOriginal,
        diferencia: diferenciaOriginal,
        estado: estado,
        observacion: fechaInfo.observacion
      });

      // Aplicar color al texto de 'diferencia'
      const diferenciaCell = row.getCell('diferencia');
      diferenciaCell.font = {
        color: { argb: esTardanza ? 'FFFF0000' : 'FF000000' } // Rojo si es tardanza, negro si llegó temprano o a tiempo
      };

      currentRow++;
    });

    // Calcular totales para el docente
    let totalHorasDocente = 0;
    let totalDiferenciaDocente = 0;
    let totalAsistenciasDocente = 0;
    let totalFaltasDocente = 0;
    let totalTardanzasDocente = 0;
    let totalObservacionesDocente = 0;

    info.fechas.forEach(fechaInfo => {
      const estado = fechaInfo.estado;
      if (estado !== 'Faltó' && estado !== 'Observado') {
        const entradaStr = fechaInfo.entrada;
        const salidaStr = fechaInfo.salida;
        const diferenciaStr = fechaInfo.diferencia;

        if (entradaStr && salidaStr) {
          totalHorasDocente += calcularTiempo(entradaStr, salidaStr);
          totalAsistenciasDocente++;
        }

        if (diferenciaStr && estado === 'Tardanza') {
          const diferenciaMinutos = parseTimeToSignedMinutes(diferenciaStr);
          totalDiferenciaDocente += diferenciaMinutos;
          totalTardanzasDocente++;
        }
      } else if (estado === 'Faltó') {
        totalFaltasDocente++;
      } else if (estado === 'Observado') {
        totalObservacionesDocente++;
      }
    });

    // Añadir fila de totales al final dentro de la tabla
    const totalRow = allSheet.addRow({
      docente: 'Totales',
      tiempoTotal: formatoHoras(totalHorasDocente),
      diferencia: formatMinutesToHHMMSS(totalDiferenciaDocente)
    });

    // Aplicar estilos a la fila de totales
    totalRow.eachCell((cell, colNumber) => {
      cell.font = { bold: true, size: 14, color: { argb: 'FF000000' } };
      cell.alignment = { horizontal: 'center', vertical: 'middle' };
      cell.border = {
        top: { style: 'thin', color: { argb: 'FF000000' } },
        left: { style: 'thin', color: { argb: 'FF000000' } },
        bottom: { style: 'thin', color: { argb: 'FF000000' } },
        right: { style: 'thin', color: { argb: 'FF000000' } }
      };
      cell.fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'FFE0FFFF' }
      };

      // Aplicar color al texto de 'diferencia' total
      if (colNumber === allSheet.columns.findIndex(col => col.key === 'diferencia') + 1) {
        cell.font.color = { argb: 'FFFF0000' }; // Red color for total 'diferencia'
      }
    });

    // Add summary table
    currentRow += 2; // Leave a gap after the data rows

    // Headers for summary table
    const summaryHeaders = ['Asistencias', 'Faltas', 'Tardanzas', 'Observaciones'];
    const summaryHeaderRow = allSheet.addRow(summaryHeaders);
    summaryHeaderRow.eachCell((cell, colNumber) => {
      cell.font = { bold: true };
      cell.alignment = { horizontal: 'center' };
      cell.fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'FFADD8E6' } // Light blue color
      };
      cell.border = {
        top: { style: 'thin' },
        left: { style: 'thin' },
        bottom: { style: 'thin' },
        right: { style: 'thin' }
      };
    });

    // Values for summary table
    const summaryValues = [
      totalAsistenciasDocente,
      totalFaltasDocente,
      totalTardanzasDocente,
      totalObservacionesDocente
    ];
    const valueRow = allSheet.addRow(summaryValues);
    valueRow.eachCell((cell, colNumber) => {
      cell.alignment = { horizontal: 'center' };
      cell.border = {
        top: { style: 'thin' },
        left: { style: 'thin' },
        bottom: { style: 'thin' },
        right: { style: 'thin' }
      };
      if (colNumber === 2 || colNumber === 3) {
        cell.font = { color: { argb: 'FFFF0000' } }; // Red color for 'Faltas' and 'Tardanzas'
      }
    });

    currentRow += 2; // Move to the next row after summary table

    // Añadir una fila vacía entre docentes
    currentRow++;
  }

  // Aplicar estilos a las filas de datos
  allSheet.eachRow((row, rowNumber) => {
    if (rowNumber !== 1) {
      row.eachCell((cell, colNumber) => {
        cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
        cell.border = {
          top: { style: 'thin', color: { argb: 'FF000000' } },
          left: { style: 'thin', color: { argb: 'FF000000' } },
          bottom: { style: 'thin', color: { argb: 'FF000000' } },
          right: { style: 'thin', color: { argb: 'FF000000' } }
        };

        // Aplicar color al texto de 'tiempoTotal'
        if (allSheet.columns[colNumber - 1].key === 'tiempoTotal') {
          cell.font = { color: { argb: 'FF0000FF' } }; // Azul para 'tiempoTotal'
        }
      });
    }
  });

  // Calcular totales para "Todos los docentes"
  let totalHorasTrabajadas = 0;
  let totalDiferenciaMinutos = 0;

  allSheet.eachRow((row, rowNumber) => {
    if (rowNumber !== 1) {
      const estado = row.getCell('estado').value;
      if (estado !== 'Faltó' && estado !== 'Observado') {
        const entradaStr = row.getCell('entrada').value;
        const salidaStr = row.getCell('salida').value;
        const diferenciaStr = row.getCell('diferencia').value;

        if (entradaStr && salidaStr) {
          totalHorasTrabajadas += calcularTiempo(entradaStr, salidaStr);
        }

        if (diferenciaStr && estado === 'Tardanza') {
          const diferenciaMinutos = parseTimeToSignedMinutes(diferenciaStr);
          totalDiferenciaMinutos += diferenciaMinutos;
        }
      }
    }
  });

  // Generar el archivo Excel y descargarlo
  workbook.xlsx.writeBuffer().then(data => {
    const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    const anchor = document.createElement('a');
    anchor.href = url;
    anchor.download = 'Datos_Docentes.xlsx';
    anchor.click();

    window.URL.revokeObjectURL(url);
  }).catch(err => {
    console.error('Error generating Excel file:', err);
  });
}

// Asegurarse de que el código se ejecute después de que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
  const exportButton = document.getElementById('exportButton');
  if (!exportButton.dataset.listenerAttached) {
    exportButton.addEventListener('click', function() {
      if (docentesData && Object.keys(docentesData).length > 0) {
        exportarExcel(docentesData); // Exportar a Excel si hay datos
      } else {
        alert("Por favor, procesa el archivo antes de exportar.");
      }
    });
    exportButton.dataset.listenerAttached = true;
  }
});

// Funciones auxiliares para parsear y formatear tiempos
function parseTimeToHours(timeStr) {
  const parts = timeStr.split(':').map(Number);
  if (parts.length >= 2) {
    const [hours, minutes] = parts;
    return hours + minutes / 60;
  }
  return 0;
}

// Modificar la función para manejar diferencias con signos
function parseTimeToSignedMinutes(timeStr) {
  if (!timeStr) return 0;

  let sign = 1;
  if (timeStr.startsWith('-')) {
    sign = -1;
    timeStr = timeStr.substring(1);
  }

  const parts = timeStr.split(':').map(Number);
  if (parts.length >= 2) {
    const [hours, minutes] = parts;
    return sign * (hours * 60 + minutes);
  }
  return 0;
}

function formatMinutesToHHMMSS(totalMinutes) {
  const sign = totalMinutes < 0 ? '-' : '';
  let minutes = Math.abs(totalMinutes);
  const hours = Math.floor(minutes / 60);
  minutes = minutes % 60;
  const secs = 0;
  return `${sign}${hours.toString().padStart(2, '0')}:${minutes
    .toString()
    .padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

// Modificar la función para parsear tiempos sin signo
function parseTimeToHours(timeStr) {
  const parts = timeStr.split(':').map(Number);
  if (parts.length >= 2) {
    const [hours, minutes] = parts;
    return hours + minutes / 60;
  }
  return 0;
}

function parseTimeToMinutes(timeStr) {
  const parts = timeStr.split(':').map(Number);
  if (parts.length >= 2) {
    const [hours, minutes] = parts;
    return hours * 60 + minutes;
  }
  return 0;
}

// Modificar la función para formatear minutos a HH:MM:SS sin signo
function formatMinutesToHHMMSS(totalMinutes) {
  let minutes = totalMinutes;
  const hours = Math.floor(minutes / 60);
  minutes = minutes % 60;
  const secs = 0;
  return `${hours.toString().padStart(2, '0')}:${minutes
    .toString()
    .padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

function calcularTiempo(inicio, fin) {
  if (!inicio || !fin) return 0;
  const [horaInicio, minutoInicio] = inicio.split(':').map(Number);
  const [horaFin, minutoFin] = fin.split(':').map(Number);
  const totalMinutosInicio = horaInicio * 60 + minutoInicio;
  const totalMinutosFin = horaFin * 60 + minutoFin;
  return totalMinutosFin - totalMinutosInicio;
}
