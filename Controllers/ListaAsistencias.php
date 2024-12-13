<?php 

	class ListaAsistencias extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
				die();
			}
			getPermisos(MSUSCRIPTORES);
		}

		public function ListaAsistencias()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Lista Asistencias";
			$data['page_title'] = "Lista Asistencias <small>I.E Lopez Albujar</small>";
			$data['page_name'] = "tcontrato";
			$data['page_functions_js'] = "functions_ListaAsistencias.js";
			$data['asistencias'] = $this->model->selectAsistencia(); // Fetch data and pass to view
			$this->views->getView($this,"listaasistencias",$data);
		}
		public function getListaAsistencias(){
			if($_SESSION['permisosMod']['r']){
				$idpersona = "";
				if( $_SESSION['userData']['idrol'] == RCLIENTES ){
					$idpersona = $_SESSION['userData']['idpersona'];
				}
				$arrData = $this->model->selectListaAsistencias($idpersona);
				//dep($arrData);
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getAsistencia(){
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectAsistencia();
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getFaltasRecientes() {
			if($_SESSION['permisosMod']['r']) {
				$fecha_actual = date('Y-m-d');
				$arrData = $this->model->getFaltasRecientes($fecha_actual);
				echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	}
?>