let tableContactos;
tableContactos = $('#tableContactos').dataTable({
    "aProcessing": true,
    "aServerSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax": {
        "url": base_url + "/contactos/getContactos",
        "dataSrc": ""
    },
    "columns": [
        { "data": "id" },
        { "data": "nombre_completo" },
        { "data": "fecha_mensaje" },
        { "data": "fecha_justificar" },
        { "data": "mensaje" },
        {
            "data": "nombre_archivo", "render": function (data, type, row) {
                return data ? `<a href="${base_url}/Assets/archivosupload/${data}" download class="btn btn-primary btn-sm">Descargar</a>` : 'No hay archivo';
            }
        },
        { "data": "estado" },
        { "data": "options" }
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
            "titleAttr": "Esportar a Excel",
            "className": "btn btn-success"
        }, {
            "extend": "pdfHtml5",
            "text": "<i class='fas fa-file-pdf'></i> PDF",
            "titleAttr": "Esportar a PDF",
            "className": "btn btn-danger"
        }, {
            "extend": "csvHtml5",
            "text": "<i class='fas fa-file-csv'></i> CSV",
            "titleAttr": "Esportar a CSV",
            "className": "btn btn-info"
        }
    ],
    "responsive": "true",
    "bDestroy": true,
    "iDisplayLength": 10,
    "order": [[0, "desc"]]
});

document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector("#btnAprobar")) {
        document.querySelector("#btnAprobar").addEventListener('click', function () {
            let idmensaje = document.querySelector("#celCodigo").innerText;
            let observaciones = document.querySelector("#txtObservaciones").value;
            aprobarMensaje(idmensaje, observaciones);
        });
    }

    if (document.querySelector("#btnRechazar")) {
        document.querySelector("#btnRechazar").addEventListener('click', function () {
            let idmensaje = document.querySelector("#celCodigo").innerText;
            let observaciones = document.querySelector("#txtObservaciones").value;
            rechazarMensaje(idmensaje, observaciones);
        });
    }
});

function aprobarMensaje(idmensaje, observaciones) {
    let request = (window.XMLHttpRequest) ?
        new XMLHttpRequest() :
        new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/contactos/aprobarMensaje';
    let formData = new FormData();
    formData.append("idmensaje", idmensaje);
    formData.append("observaciones", observaciones);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                swal("", objData.msg, "success");
                $('#modalViewMensaje').modal('hide');
                tableContactos.api().ajax.reload();
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}

function rechazarMensaje(idmensaje, observaciones) {
    let request = (window.XMLHttpRequest) ?
        new XMLHttpRequest() :
        new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/contactos/rechazarMensaje';
    let formData = new FormData();
    formData.append("idmensaje", idmensaje);
    formData.append("observaciones", observaciones);
    formData.append("fecha_justificacion", document.querySelector("#celMensajeFecha").innerText); // Ensure the date to justify is sent
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                swal("", objData.msg, "success");
                $('#modalViewMensaje').modal('hide');
                tableContactos.api().ajax.reload();
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}

function fntViewInfo(idmensaje) {
    let request = (window.XMLHttpRequest) ?
        new XMLHttpRequest() :
        new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/contactos/getMensaje/' + idmensaje;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                let objMesaje = objData.data;
                document.querySelector("#celCodigo").innerHTML = objMesaje.id;
                document.querySelector("#celNombre").innerHTML = objMesaje.nombre_completo;
                document.querySelector("#celEmail").innerHTML = objMesaje.email;
                document.querySelector("#celFecha").innerHTML = objMesaje.fecha;
                document.querySelector("#celMensaje").innerHTML = objMesaje.mensaje;
                document.querySelector("#celArchivo").innerHTML = objMesaje.nombre_archivo ? `<a href="${base_url}/Assets/archivosupload/${objMesaje.nombre_archivo}" download class="btn btn-primary btn-sm">Descargar archivo</a>` : 'No hay archivo';
                document.querySelector("#celMensajeFecha").innerHTML = objMesaje.fecha_justificar;
                document.querySelector("#idmensaje").value = objMesaje.id; // Set hidden input value
                document.querySelector("#idJustificacion").value = objMesaje.id_justificacion; // Set hidden input value for justification ID
                $('#modalViewMensaje').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}

