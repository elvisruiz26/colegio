<?php 

class ListaAsistenciasModel extends Mysql{

	public function selectListaAsistencias($idpersona = null){
		$where = "";
		if($idpersona != null){
			$where = " WHERE p.idpersona = ".$idpersona;
		}
		$sql = "SELECT p.nombres, p.apellidos, e.estado,
    			DATE_FORMAT(a.Fecha, '%d/%m/%Y') as Fecha, a.HoraEntrada, a.HoraSalida 
				FROM asistencia a 
				JOIN persona p ON a.COD_Docente = p.codigoasistencia 
				JOIN estadoasistencia e ON a.EstadoAsistencia = e.ID_EstadoAsistencia $where";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectAsistencia()
	{
		$sql = "SELECT p.nombres, p.apellidos, e.estado,
    			DATE_FORMAT(a.Fecha, '%d/%m/%Y') as Fecha, a.HoraEntrada, a.HoraSalida
				FROM asistencia a 
				JOIN persona p ON a.COD_Docente = p.codigoasistencia 
				JOIN estadoasistencia e ON a.EstadoAsistencia = e.ID_EstadoAsistencia 
				ORDER BY Fecha DESC";
		$request = $this->select_all($sql);
		return $request;
	}

	public function getFaltasRecientes($fecha) {
		$sql = "SELECT p.nombres, p.apellidos, a.Fecha, a.EstadoAsistencia 
				FROM persona p 
				INNER JOIN asistencia a ON p.idpersona = a.idpersona 
				WHERE a.EstadoAsistencia = 2
				AND a.Fecha = '{$fecha}'";
		$request = $this->select_all($sql);
		return $request;
	}

}
?>