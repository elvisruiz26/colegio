<?php 
	class Contacto extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			getPermisos(MDPAGINAS);
			$this->model = new ContactoModel();
		}

		public function contacto()
		{
			$pageContent = getPageRout('contacto');
			if(empty($pageContent)){
				header("Location: ".base_url());
			}else{
				$data['page_tag'] = NOMBRE_EMPESA;
				$data['page_title'] = NOMBRE_EMPESA." - ".$pageContent['titulo'];
				$data['page_name'] = $pageContent['titulo'];
				$data['page'] = $pageContent;
				$this->views->getView($this,"contacto",$data); 
			}

		}

		public function enviar()
		{
			if($_POST){
				// Verificar si la sesión está iniciada y obtener el ID de usuario
				if(session_status() == PHP_SESSION_NONE){
					session_start();
				}
				if(isset($_SESSION['idUser'])){
					$usuario_id = $_SESSION['idUser'];
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Usuario no autenticado.');
					echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
					die();
				}

				$mensaje = strClean($_POST['mensaje']);
				$ip = $_SERVER['REMOTE_ADDR'];
				$dispositivo = substr($_SERVER['HTTP_USER_AGENT'], 0, 25); // Adjust length to fit your database column
				$useragent = substr($_SERVER['HTTP_USER_AGENT'], 0, 255); // Adjust length to fit your database column
				$fecha_justificar = isset($_POST['fecha']) ? $_POST['fecha'] : null;
				$idstatus = isset($_POST['idstatus']) ? intval($_POST['idstatus']) : 2; // Ensure idstatus has a default value of 2

				// Debugging information
				error_log("usuario_id: " . $usuario_id);
				error_log("mensaje: " . $mensaje);
				error_log("ip: " . $ip);
				error_log("dispositivo: " . $dispositivo);
				error_log("useragent: " . $useragent);
				error_log("fecha_justificar: " . $fecha_justificar);
				error_log("idstatus: " . $idstatus);

				// Manejar la carga del archivo
				if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK){
					$archivo = $_FILES['archivo'];

					// Validar el tamaño del archivo (por ejemplo, máximo 5 MB)
					if($archivo['size'] > 5 * 1024 * 1024){
						$arrResponse = array('status' => false, 'msg' => 'El archivo es demasiado grande. Máximo 5MB.');
						echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
						die();
					}

					// Generar un nombre único para el archivo
					$nombreArchivo = 'evidencia_'.uniqid().'.'.pathinfo($archivo['name'], PATHINFO_EXTENSION);
					$destino = 'Assets/archivosupload/'.$nombreArchivo;

					// Mover el archivo al directorio destino
					if(move_uploaded_file($archivo['tmp_name'], $destino)){
						// Archivo movido correctamente
					} else {
						$arrResponse = array('status' => false, 'msg' => 'No se pudo guardar el archivo.');
						echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
						die();
					}

				} else {
					$nombreArchivo = null;
				}

				// Enviar datos al modelo
				$request = $this->model->setContacto($usuario_id, $mensaje, $ip, $dispositivo, $useragent, $nombreArchivo, $fecha_justificar, $idstatus);
				if($request > 0){
					$arrResponse = array('status' => true, 'msg' => 'Evidencia enviada correctamente.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'No es posible enviar la evidencia.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	}
?>
