let gridInstance = null; 

function generarTabla(data) {
  gridInstance = new gridjs.Grid({ 
    columns: [
      "Nombre",
      "ID",
      "Fecha",
      "Turno",
      "Entrada",
      "Salida",
      "Tiempo Total",
      "Diferencia",
      "Estado"
    ],
    language: {
      'search': {
        'placeholder': '🔍 buscar...'
      },
      'pagination': {
        'previous': '⬅️',
        'next': '➡️',
        'showing': '😃 Observando',
        'results': () => 'Asistencias'
      }
    },
    data: data,
    search: true,
    pagination: {
      enabled: true,
      limit: 8
    }
  }).render(document.getElementById("wrapper"));
}

function exportToXlsx(gridjsData) {
  const ws = XLSX.utils.json_to_sheet(gridjsData);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Hoja1');
  XLSX.writeFile(wb, 'tabla.xlsx');
}

function exportToPdf(gridjsData) {
  const doc = new jsPDF();
  doc.autoTable({
    head: [Object.keys(gridjsData[0])],
    body: gridjsData.map(row => Object.values(row)),
  });
  doc.save('tabla.pdf');
}