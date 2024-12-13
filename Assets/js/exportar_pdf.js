// Asegúrate de incluir las bibliotecas jspdf y jspdf-autotable en tu HTML
// <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
// <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

document.addEventListener('DOMContentLoaded', function() {
  const exportPdfButton = document.getElementById('exportPdfButton');
  if (!exportPdfButton.dataset.listenerAttached) {
    exportPdfButton.addEventListener('click', function () {
      if (!docentesData || Object.keys(docentesData).length === 0) {
        alert("No hay datos para exportar. Procesa un archivo primero.");
        return;
      }

      // Ensure jsPDF is correctly imported
      const { jsPDF } = window.jspdf;

      // Initialize jsPDF
      const pdf = new jsPDF('landscape', 'mm', 'a4');

      let totalAsistencias = 0;
      let totalFaltas = 0;
      let totalTardanzas = 0;
      let totalObservaciones = 0;

      for (const [nombre, info] of Object.entries(docentesData)) {
        pdf.setFontSize(16);
        pdf.text(`Docente: ${nombre} (ID: ${info.id})`, 14, 15);

        const headers = [["Fecha", "Turno", "Entrada", "Salida", "Horas Trabajadas", "Tardanzas", "Estado", "Observación"]];

        const data = info.fechas.map(fechaInfo => [
          fechaInfo.fecha,
          fechaInfo.turno,
          fechaInfo.entrada,
          fechaInfo.salida,
          fechaInfo.tiempoTotal,
          fechaInfo.diferencia,
          fechaInfo.estado,
          fechaInfo.observacion
        ]);

        // Calculate totals for the docente
        let totalHorasDocente = 0;
        let totalDiferenciaDocente = 0;
        let totalFaltasDocente = 0;
        let totalTardanzasDocente = 0;
        let totalObservacionesDocente = 0;
        let totalAsistenciasDocente = 0;

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

        // Add totals row
        const totalRow = [
          'Totales',
          '',
          '',
          '',
          formatoHoras(totalHorasDocente),
          formatMinutesToHHMMSS(totalDiferenciaDocente),
          '',
          ''
        ];

        // Add totalRow to data
        data.push(totalRow);

        pdf.autoTable({
          startY: 20,
          head: headers,
          body: data,
          theme: 'grid',
          styles: {
            halign: 'center',
            valign: 'middle',
            fontSize: 10,
            cellPadding: 2,
            overflow: 'linebreak'
          },
          headStyles: {
            fillColor: [173, 216, 230], // Color celeste
            textColor: [0, 0, 0],
            fontStyle: 'bold'
          },
          columnStyles: {
            0: { cellWidth: 'wrap' },  // Fecha
            1: { cellWidth: 'wrap' },  // Turno
            2: { cellWidth: 'wrap' },  // Entrada
            3: { cellWidth: 'wrap' },  // Salida
            4: { cellWidth: 'wrap' },  // Tiempo Total
            5: { cellWidth: 'wrap' },  // Diferencia
            6: { cellWidth: 'wrap' },  // Estado
            7: { cellWidth: 'wrap' }   // Observación
          },
          didParseCell: function (data) {
            if (data.section === 'body' && data.column.index === 5) {
              const cellValue = data.cell.raw;
              if (cellValue && cellValue.includes('-')) {
                data.cell.styles.textColor = [255, 0, 0]; // Red color for tardanzas (negative difference)
              } else {
                data.cell.styles.textColor = [0, 0, 0]; // Black color for other values
              }
            }
            // Check if it's the totals row and the 'diferencia' column
            if (
              data.section === 'body' &&
              data.row.index === data.table.body.length - 1 &&
              data.column.index === 5
            ) {
              data.cell.styles.textColor = [255, 0, 0]; // Red color for total 'diferencia'
            }
          }
        });

        // Add summary table
        const summaryHeaders = [["Asistencias", "Faltas", "Tardanzas", "Observaciones"]];
        const summaryData = [[
          totalAsistenciasDocente.toString(),
          totalFaltasDocente.toString(),
          totalTardanzasDocente.toString(),
          totalObservacionesDocente.toString()
        ]];

        pdf.autoTable({
          startY: pdf.lastAutoTable.finalY + 10,
          head: summaryHeaders,
          body: summaryData,
          theme: 'grid',
          styles: {
            halign: 'center',
            valign: 'middle',
            fontSize: 12,
            cellPadding: 2
          },
          headStyles: {
            fillColor: [173, 216, 230],
            textColor: [0, 0, 0],
            fontStyle: 'bold'
          },
          bodyStyles: {
            textColor: [0, 0, 0]
          },
          didParseCell: function (data) {
            if (data.section === 'body' && (data.column.index === 1 || data.column.index === 2)) {
              data.cell.styles.textColor = [255, 0, 0]; // Red color for 'Faltas' and 'Tardanzas'
            }
          }
        });

        // Add a new page if not the last docente
        if (Object.keys(docentesData).indexOf(nombre) !== Object.keys(docentesData).length - 1) {
          pdf.addPage();
        }
      }

      pdf.save('Datos_Docentes.pdf');
    });
    exportPdfButton.dataset.listenerAttached = true;
  }
});

// Helper functions
function calcularTiempo(inicio, fin) {
  if (!inicio || !fin) return 0;
  const [horaInicio, minutoInicio] = inicio.split(':').map(Number);
  const [horaFin, minutoFin] = fin.split(':').map(Number);
  const totalMinutosInicio = horaInicio * 60 + minutoInicio;
  const totalMinutosFin = horaFin * 60 + minutoFin;
  return totalMinutosFin - totalMinutosInicio;
}

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

function formatoHoras(minutos) {
  const horas = Math.floor(minutos / 60);
  const mins = minutos % 60;
  const segs = 0; // Asumiendo que no tienes segundos en tus datos
  return `${horas.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;
}

function formatMinutesToHHMMSS(totalMinutes) {
  const sign = totalMinutes < 0 ? '-' : '';
  let minutes = Math.abs(totalMinutes);
  const hours = Math.floor(minutes / 60);
  minutes = minutes % 60;
  const secs = 0;
  return `${sign}${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}
