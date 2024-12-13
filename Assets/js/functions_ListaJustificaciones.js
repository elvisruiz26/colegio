let tableListaAsistencias;
tableListaAsistencias = $('#tableJustificaciones').dataTable({
    "aProcessing": true,
    "aServerSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax": {
        "url": base_url + "/Justificaciones/getListaJustificaciones",
        "dataSrc": ""
    },
    "columns": [
        {"data": "nombres"},
        {"data": "apellidos"},
        {"data": "nombre"},
        {"data": "Fecha"},
        {"data": "descripcion"},
        {
            "data": null,
            "render": function(data, type, row) {
                if (row.nombre === 'RECHAZADO') {
                    return `<a href="${base_url}/contacto?fecha=${row.Fecha}" class="btn btn-primary">Justificar</a>`;
                }
                return '';
            }
        }
    ],
    'dom': 'lBfrtip',
    'buttons': [
        {
            "extend": "copyHtml5",
            "text": "<i class='far fa-copy'></i> Copiar",
            "titleAttr": "Copiar",
            "className": "btn btn-secondary"
        }, {
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Excel",
            "titleAttr": "Exportar a Excel",
            "className": "btn btn-success"
        }, {
            "extend": "pdfHtml5",
            "text": "<i class='fas fa-file-pdf'></i> PDF",
            "titleAttr": "Exportar a PDF",
            "className": "btn btn-danger"
        }, {
            "extend": "csvHtml5",
            "text": "<i class='fas fa-file-csv'></i> CSV",
            "titleAttr": "Exportar a CSV",
            "className": "btn btn-info"
        }
    ],
    "resonsieve": "true",
    "bDestroy": true,
    "iDisplayLength": 10,
    "order": [[0, "desc"]]
});