<?php 

class ContactosModel extends Mysql{

	public function selectContactos()
	{
		$sql = "SELECT c.id, CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo, c.mensaje, DATE_FORMAT(c.datecreated, '%d/%m/%Y') as fecha_mensaje, c.nombre_archivo, s.nombre as estado, DATE_FORMAT(c.fecha_justificar, '%d/%m/%Y') as fecha_justificar
				FROM contacto c
				INNER JOIN persona p ON c.usuario_id = p.idpersona
				INNER JOIN statusjustificacion s ON c.idstatus = s.idstatus
				ORDER BY c.id DESC";
		$request = $this->select_all($sql);
		error_log("selectContactos: " . print_r($request, true)); // Debugging information
		return $request;
	}

	public function selectMensaje(int $idmensaje){
		$sql = "SELECT c.id, CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo, p.email_user as email, DATE_FORMAT(c.datecreated, '%d/%m/%Y') as fecha, c.mensaje, c.nombre_archivo, s.nombre as estado, c.usuario_id, DATE_FORMAT(c.fecha_justificar, '%d/%m/%Y') as fecha_justificar
				FROM contacto c
				INNER JOIN persona p ON c.usuario_id = p.idpersona
				INNER JOIN statusjustificacion s ON c.idstatus = s.idstatus
				WHERE c.id = {$idmensaje}";
		$request = $this->select($sql);
		error_log("selectMensaje: " . print_r($request, true)); // Debugging information
		return $request;
	}

}
?>