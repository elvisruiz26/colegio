<?php 
class Asistencias extends Controllers{	
	public function __construct()
	{
		parent::__construct();
		session_start();
		if(empty($_SESSION['login']))
		{
			header('Location: '.base_url().'/login');
			die();
		}
		getPermisos(MPEDIDOS);
	}

	public function Asistencias()
	{
		if(empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Asistencia";
		$data['page_title'] = "Asistencia <small>I.E Lopez Albujar</small>";
		$data['page_name'] = "Asistencias";
		$data['page_functions_js'] = "functions_asistencias.js";
		$this->views->getView($this,"Asistencias",$data);
	}

	public function subirDatos() {
		$inputData = json_decode(file_get_contents('php://input'), true);

		if (isset($inputData['docentesData']) && isset($inputData['stateMapping'])) {
			$data = $inputData['docentesData'];
			$stateMapping = $inputData['stateMapping'];

			try {
				foreach ($data as $docente => $info) {
					if (isset($info['fechas'])) {
						foreach ($info['fechas'] as $fechaInfo) {
							$id = $info['id'];
							$fecha = $fechaInfo['fecha'];
							$entrada = $fechaInfo['entrada'];
							$salida = $fechaInfo['salida'];
							$estado = $stateMapping[$fechaInfo['estado']];
							$observacion = $fechaInfo['observacion'];
							$diferencia = $fechaInfo['diferencia'];

							if (!$this->model->insertarAsistencia($id, $fecha, $entrada, $salida, $estado, $observacion, $diferencia)) {
								echo json_encode(['success' => false, 'error' => 'Error al insertar datos']);
								return;
							}
						}
					}
				}
				echo json_encode(['success' => true]);
			} catch (PDOException $e) {
				if ($e->getCode() == 23000) { // Foreign key constraint violation
					echo json_encode(['success' => false, 'error' => 'Algunos docentes no están registrados']);
				} else {
					echo json_encode(['success' => false, 'error' => 'Error al insertar datos']);
				}
			}
		} else {
			echo json_encode(['success' => false, 'error' => 'No data received']);
		}
	}
	
	public function setAsistecia(){
		error_reporting(0);
		if($_POST){
			if(empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtCodigoAsistencia']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['txtWhatsapp']) || empty($_POST['txtFechaNac']) || empty($_POST['txtDirUsuario']) )
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}else{ 
				$idUsuario = intval($_POST['idUsuario']);
				$strIdentificacion = strClean($_POST['txtIdentificacion']);
				$strNombre = ucwords(strClean($_POST['txtNombre']));
				$strApellido = ucwords(strClean($_POST['txtApellido']));
				$intCodigoAsistencia = intval(strClean($_POST['txtCodigoAsistencia']));
				$intTelefono = intval(strClean($_POST['txtTelefono']));
				$strEmail = strtolower(strClean($_POST['txtEmail']));
				$intWhatsapp = strClean($_POST['txtWhatsapp']);
				$strFechaNac = strClean($_POST['txtFechaNac']);
				$strDirUser = strClean($_POST['txtDirUsuario']);
				$intTipoId = 7;
				$request_user = "";
				if($idUsuario == 0)
				{
					$option = 1;
					$strPassword =  empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
					$strPasswordEncript = hash("SHA256",$strPassword);
					if($_SESSION['permisosMod']['w']){
						$request_user = $this->model->insertDocente($strIdentificacion,
																			$strNombre, 
																			$strApellido, 
																			$intCodigoAsistencia,
																			$intTelefono, 
																			$strEmail,
																			$strPasswordEncript,
																			$intTipoId, 
																			$intWhatsapp,
																			$strFechaNac,
																			$strDirUser);
					}
				}else{
					$option = 2;
					$strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
					if($_SESSION['permisosMod']['u']){
						$request_user = $this->model->updateDocente($idUsuario,
																	$strIdentificacion, 
																	$strNombre,
																	$strApellido, 
																	$intCodigoAsistencia,
																	$intTelefono, 
																	$strEmail,
																	$strPassword, 
																	$intWhatsapp,
																	$strFechaNac, 
																	$strDirUser);
					}
				}

				if($request_user > 0 )
				{
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						$nombreUsuario = $strNombre.' '.$strApellido;
						$dataUsuario = array('nombreUsuario' => $nombreUsuario,
											 'email' => $strEmail,
											 'password' => $strPassword,
											 'asunto' => 'Bienvenido a la I.E Lopez Albujar',);
						sendEmail($dataUsuario,'email_bienvenida');
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}else if($request_user == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');		
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}
}
 ?>