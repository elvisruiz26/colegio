<?php 

	class Contactos extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
				die();
			}
			getPermisos(MDCONTACTOS);
		}

		public function Contactos()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Contactos";
			$data['page_title'] = "CONTACTOS <small>I.E Lopez Albujar</small>";
			$data['page_name'] = "contactos";
			$data['page_functions_js'] = "functions_contactos.js";
			$this->views->getView($this,"contactos",$data);
		}

		public function getContactos(){
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectContactos();
				error_log("getContactos: " . print_r($arrData, true)); // Debugging information
				for ($i=0; $i < count($arrData) ; $i++) { 
					$btnView = '';
					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['id'].')" title="Ver mensaje"><i class="far fa-eye"></i></button>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getMensaje($idmensaje){
			if($_SESSION['permisosMod']['r']){
				$idmensaje = intval($idmensaje);
				if($idmensaje > 0){
					$arrData = $this->model->selectMensaje($idmensaje);
					error_log("getMensaje: " . print_r($arrData, true)); // Debugging information
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function aprobarMensaje() {
			if ($_POST) {
				$idmensaje = intval($_POST['idmensaje']);
				$observaciones = strClean($_POST['observaciones']);
				$idstatus = 1; // APROBADO
				$estadoAsistencia = 4; // Justificó
	
				// Get message details
				$mensajeData = $this->model->selectMensaje($idmensaje);
				if (empty($mensajeData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$usuario_id = $mensajeData['usuario_id'];
					$fecha_justificar = date('Y-m-d', strtotime(str_replace('/', '-', $mensajeData['fecha_justificar'])));
	
					// Insert into justificaciones
					$query_insert = "INSERT INTO justificaciones(descripcion, EstadoAsistencia, fecha_justificacion, idstatus, usuario_id) 
									 VALUES(?,?,?,?,?)";
					$arrData = array($observaciones, $estadoAsistencia, $fecha_justificar, $idstatus, $usuario_id);
					error_log("arrData (aprobarMensaje): " . print_r($arrData, true)); // Debugging information
					$request_insert = $this->model->insert($query_insert, $arrData);
	
					if ($request_insert > 0) {
						// Update asistencia
						$query_update_asistencia = "UPDATE asistencia SET EstadoAsistencia = ?, ID_Justificacion = ? WHERE idpersona = ? AND Fecha = ?";
						$arrDataUpdate = array($estadoAsistencia, $request_insert, $usuario_id, $fecha_justificar);
						$this->model->update($query_update_asistencia, $arrDataUpdate);
	
						// Update contacto
						$query_update_contacto = "UPDATE contacto SET idstatus = ? WHERE id = ?";
						$arrDataUpdateContacto = array($idstatus, $idmensaje);
						$this->model->update($query_update_contacto, $arrDataUpdateContacto);
	
						$arrResponse = array('status' => true, 'msg' => 'Mensaje aprobado correctamente.');
					} else {
						$arrResponse = array('status' => false, 'msg' => 'No es posible aprobar el mensaje.');
					}
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
			die();
		}
	
		public function rechazarMensaje() {
			if ($_POST) {
				$idmensaje = intval($_POST['idmensaje']);
				$observaciones = strClean($_POST['observaciones']);
				$idstatus = 3; // RECHAZADO
				$estadoAsistencia = 2; // Falta
				$fecha_justificar = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fecha_justificacion']))); // Format the date to justify
	
				// Get message details
				$mensajeData = $this->model->selectMensaje($idmensaje);
				if (empty($mensajeData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$usuario_id = $mensajeData['usuario_id'];
	
					// Insert into justificaciones
					$query_insert = "INSERT INTO justificaciones(descripcion, EstadoAsistencia, fecha_justificacion, idstatus, usuario_id) 
									 VALUES(?,?,?,?,?)";
					$arrData = array($observaciones, $estadoAsistencia, $fecha_justificar, $idstatus, $usuario_id);
					error_log("arrData (rechazarMensaje): " . print_r($arrData, true)); // Debugging information
					$request_insert = $this->model->insert($query_insert, $arrData);
	
					if ($request_insert > 0) {
						// Update contacto
						$query_update_contacto = "UPDATE contacto SET idstatus = ? WHERE id = ?";
						$arrDataUpdateContacto = array($idstatus, $idmensaje);
						$this->model->update($query_update_contacto, $arrDataUpdateContacto);
	
						$arrResponse = array('status' => true, 'msg' => 'Mensaje rechazado correctamente.');
					} else {
						$arrResponse = array('status' => false, 'msg' => 'No es posible rechazar el mensaje.');
					}
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	}
?>