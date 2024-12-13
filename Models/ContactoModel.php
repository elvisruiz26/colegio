<?php 

class ContactoModel extends Mysql{

    public function __construct()
    {
        parent::__construct();
    }

    public function setContacto(int $usuario_id, string $mensaje, string $ip, string $dispositivo, string $useragent, string $nombreArchivo = null, string $fecha_justificar = null, int $idstatus = 2){
        $dispositivo = substr($dispositivo, 0, 25); // Adjust length to fit your database column
        $useragent = substr($useragent, 0, 100); // Adjust length to fit your database column
        $query_insert  = "INSERT INTO contacto(usuario_id, mensaje, ip, dispositivo, useragent, nombre_archivo, fecha_justificar, idstatus) 
                          VALUES(?,?,?,?,?,?,?,?)";
        $arrData = array($usuario_id, $mensaje, $ip, $dispositivo, $useragent, $nombreArchivo, $fecha_justificar, $idstatus);
        error_log("arrData: " . print_r($arrData, true)); // Debugging information
        $request_insert = $this->insert($query_insert,$arrData);
        return $request_insert;
    }

}
?>