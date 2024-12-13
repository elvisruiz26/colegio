$('.date-picker').datepicker( {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['1 -', '2 -', '3 -', '4 -', '5 -', '6 -', '7 -', '8 -', '9 -', '10 -', '11 -', '12 -'],
    monthNamesShort: ['Enero','Febrero','Marzo','Abril', 'Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'],
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    showDays: false,
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    }
});

function fntSearchPagos(){
    let fecha = document.querySelector(".pagoMes").value;
    if(fecha == ""){
        swal("", "Seleccione mes y año" , "error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/selectPagosMes';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('fecha',fecha);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                $("#pagosMesAnio").html(request.responseText);
                divLoading.style.display = "none";
                return false;
            }
        }
    }
}

function fntSearchVMes(){
    let fecha = document.querySelector(".ventasMes").value;
    if(fecha == ""){
        swal("", "Seleccione mes y año" , "error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/ventasMes';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('fecha',fecha);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                $("#graficaMes").html(request.responseText);
                divLoading.style.display = "none";
                return false;
            }
        }
    }
}

function fntSearchVAnio(){
    let anio = document.querySelector(".ventasAnio").value;
    if(anio == ""){
        swal("", "Ingrese año " , "error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/ventasAnio';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('anio',anio);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                $("#graficaAnio").html(request.responseText);
                divLoading.style.display = "none";
                return false;
            }
        }
    }
}

// Assets/js/functions_dashboard.js

function fntSearchAsistencias(){
    let fecha = document.querySelector(".asistenciaMes").value;
    if(fecha == ""){
        swal("", "Seleccione mes y año" , "error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/reporteAsistenciasMes';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('fecha',fecha);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                $("#reporteAsistenciasMes").html(request.responseText);
                divLoading.style.display = "none";
                return false;
            } else {
                divLoading.style.display = "none";
            }
        }
    }
}

function fntSearchAsistenciasAnual(){
    let anio = document.querySelector(".ventasAnio").value;
    if(anio == ""){
        swal("", "Ingrese el año." , "error");
        return false;
    } else {
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/reporteAsistenciasAnual';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('anio', anio);
        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                document.querySelector("#reporteAsistenciasAnual").innerHTML = request.responseText;
                divLoading.style.display = "none";
            } else {
                swal("Error", "Error al obtener los datos.", "error");
            }
        }
    }
}

let chartMes;
let chartAnual;

function generarReporteMes() {
    let formData = new FormData(document.getElementById('formReporteMes'));

    let request = new XMLHttpRequest();
    let ajaxUrl = base_url + '/Dashboard/getReporteAsistenciasMes';
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            let data = JSON.parse(request.responseText);
            mostrarReporte('mensual', data);
        }
    };
}

function generarReporteAnual() {
    let formData = new FormData(document.getElementById('formReporteAnio'));

    let request = new XMLHttpRequest();
    let ajaxUrl = base_url + '/Dashboard/getReporteAsistenciasAnual';
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            let data = JSON.parse(request.responseText);
            mostrarReporte('anual', data);
        }
    };
}

function mostrarReporte(tipo, data) {
    let reporteDiv = document.getElementById(`reporte${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);
    let html = '<ul>';
    let totalAsistencias = 0;
    let totalFaltas = 0;
    let totalJustificaciones = 0;
    let totalObservaciones = 0;

    data.forEach(function(item) {
        if (item.EstadoAsistencia === 'Asistencia') {
            totalAsistencias = item.total;
        } else if (item.EstadoAsistencia === 'Falta') {
            totalFaltas = item.total;
        } else if (item.EstadoAsistencia === 'Justificación') {
            totalJustificaciones = item.total;
        } else if (item.EstadoAsistencia === 'Observación') {
            totalObservaciones = item.total;
        }
    });

    html += `<li>Asistencias: ${totalAsistencias}</li>`;
    html += `<li>Faltas: ${totalFaltas}</li>`;
    html += `<li>Justificaciones: ${totalJustificaciones}</li>`;
    html += `<li>Observaciones: ${totalObservaciones}</li>`;
    html += '</ul>';
    reporteDiv.innerHTML = html;
}

function descargarReporte(tipo) {
    let reporteDiv = document.getElementById(`reporte${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);
    let contenido = reporteDiv.innerHTML;
    let blob = new Blob([contenido], { type: 'text/plain' });
    let link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `reporte_${tipo}.txt`;
    link.click();
}

function graficarReporteMes(data) {
    if (chartMes) {
        chartMes.destroy();
    }
    let ctx = document.getElementById('graficoReporteMes').getContext('2d');
    let labels = [];
    let valores = [];
    data.forEach(function(item) {
        labels.push(item.EstadoAsistencia);
        valores.push(parseInt(item.total));
    });
    chartMes = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: ['#4caf50', '#f44336', '#ffeb3b', '#2196f3']
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Reporte Mensual de Asistencias'
            }
        }
    });
}

function graficarReporteAnual(data) {
    if (chartAnual) {
        chartAnual.destroy();
    }
    let ctx = document.getElementById('graficoReporteAnual').getContext('2d');
    let labels = [];
    let valores = [];
    data.forEach(function(item) {
        labels.push(item.EstadoAsistencia);
        valores.push(parseInt(item.total));
    });
    chartAnual = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: ['#4caf50', '#f44336', '#ffeb3b', '#2196f3']
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Reporte Anual de Asistencias'
            }
        }
    });
}