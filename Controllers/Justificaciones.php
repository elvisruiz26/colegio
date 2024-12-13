<?php 

	class Justificaciones extends Controllers{
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

		public function Justificaciones()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Lista Justificaciones";
			$data['page_title'] = "Lista Justificaciones <small>I.E Lopez Albujar</small>";
			$data['page_name'] = "tjustificaciones";
			$data['page_functions_js'] = "functions_ListaJustificaciones.js";
			$data['justificaciones'] = $this->model->selectJustificacion(); // Fetch data and pass to view
			$this->views->getView($this,"justificaciones",$data);
		}
		public function getListaJustificaciones(){
			if($_SESSION['permisosMod']['r']){
				$idpersona = "";
				if( $_SESSION['userData']['idrol'] == RCLIENTES ){
					$idpersona = $_SESSION['userData']['idpersona'];
				}
				$arrData = $this->model->selectJustificaciones($idpersona);
				//dep($arrData);
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getJustificaciones(){
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectJustificacion();
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	}
?>