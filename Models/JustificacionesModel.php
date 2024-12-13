<?php 

class JustificacionesModel extends Mysql{

	public function selectJustificaciones($idpersona = null){
		$where = "";
		if($idpersona != null){
			$where = " WHERE p.idpersona = ".$idpersona;
		}
		$sql = "SELECT p.nombres, p.apellidos, e.nombre, 
        DATE_FORMAT(j.fecha_justificacion, '%d/%m/%Y') as Fecha, j.descripcion 
		FROM justificaciones j 
        JOIN persona p ON j.usuario_id = p.idpersona 
        JOIN statusjustificacion e ON j.idstatus = e.idstatus $where";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectJustificacion()
	{
		$sql = "SELECT p.nombres, p.apellidos, e.nombre, 
                DATE_FORMAT(j.fecha_justificacion, '%d/%m/%Y') as Fecha, j.descripcion 
				FROM justificaciones j 
                JOIN persona p ON j.usuario_id = p.idpersona 
                JOIN statusjustificacion e ON j.idstatus = e.idstatus
				ORDER BY Fecha DESC";
		$request = $this->select_all($sql);
		return $request;
	}

}
?>