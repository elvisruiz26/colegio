document.addEventListener('DOMContentLoaded', function(){
    if(document.querySelector("#frmContacto")){
        let frmContacto = document.querySelector("#frmContacto");

        frmContacto.onsubmit = function(e){
            e.preventDefault();

            let mensaje = document.querySelector("#mensaje").value;
            let fecha = document.querySelector("#fecha").value;
            let idstatusElement = document.querySelector("#idstatus");
            let idstatus = idstatusElement ? idstatusElement.value : 2; // Default to 2 if not found

            if(mensaje == ""){
                swal("", "Por favor escribe tu justificación." ,"error");
                return false;
            }   

            let request = (window.XMLHttpRequest) ? 
                        new XMLHttpRequest() : 
                        new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/contacto/enviar';
            let formData = new FormData(frmContacto);
            formData.append("idstatus", idstatus); // Ensure idstatus is set in the form data
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    try {
                        let objData = JSON.parse(request.responseText);
                        if(objData.status){
                            swal("", objData.msg , "success");
                            frmContacto.reset();
                        }else{
                            swal("", objData.msg , "error");
                        }
                    } catch (e) {
                        swal("", "Error en la respuesta del servidor." ,"error");
                    }
                }
            }
            request.onerror = function() {
                swal("", "Error en la conexión." ,"error");
            }
        };
    }
});