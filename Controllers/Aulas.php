<?php 

	class Aulas extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			//session_regenerate_id(true);
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
				die();
			}
			getPermisos(MUSUARIOS);
		}

		public function Aulas()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_id'] = 3;
			$data['page_tag'] = "Aulas Usuario";
			$data['page_name'] = "aulas";
			$data['page_title'] = "Aulas <small> I.E Lopez Albujar</small>";
			$data['page_functions_js'] = "functions_aulas.js";
			$this->views->getView($this,"aulas",$data);
		}

		public function getAulas()
		{
			if($_SESSION['permisosMod']['r']){
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';
				$arrData = $this->model->selectAulas();

				for ($i=0; $i < count($arrData); $i++) {

					if($arrData[$i]['status'] == 1)
					{
						$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['u']){
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditAula" onClick="fntEditAula('.$arrData[$i]['idaula'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
					}
					if($_SESSION['permisosMod']['d']){
						$btnDelete = '<button class="btn btn-danger btn-sm btnDelAula" onClick="fntDelAula('.$arrData[$i]['idaula'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>
					</div>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getSelectAulas()
		{
			$htmlOptions = "";
			$arrData = $this->model->selectAulas();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['status'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['idaula'].'">'.$arrData[$i]['nombreaula'].'</option>';
					}
				}
			}
			echo $htmlOptions;
			die();		
		}

		public function getAula(int $idaula)
		{
			if($_SESSION['permisosMod']['r']){
				$intIdAula = intval(strClean($idaula));
				if($intIdAula > 0)
				{
					$arrData = $this->model->selectAula($intIdAula);
					if(empty($arrData))
					{
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function setAula(){
				$intIdAula = intval($_POST['idAula']);
				$strAula =  strClean($_POST['txtNombre']);
				$strDescipcion = strClean($_POST['txtNivel']);
				$intStatus = intval($_POST['listStatus']);
				$request_Aula = "";
				if($intIdAula == 0)
				{
					//Crear
					if($_SESSION['permisosMod']['w']){
						$request_rol = $this->model->insertRol($strAula, $strNivel,$intStatus);
						$option = 1;
					}
				}else{
					//Actualizar
					if($_SESSION['permisosMod']['u']){
						$request_rol = $this->model->updateRol($intIdAula, $strAula, $strNivel, $intStatus);
						$option = 2;
					}
				}

				if($request_aula > 0 )
				{
					if($option == 1)
					{
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}else if($request_aula == 'exist'){
					
					$arrResponse = array('status' => false, 'msg' => '¡Atención! La aula ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			die();
		}

		public function delAula()
		{
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intIdAula = intval($_POST['idaula']);
					$requestDelete = $this->model->deleteRol($intIdAula);
					if($requestDelete == 'ok')
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Aula');
					}else if($requestDelete == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar una Aula asociado a Horario.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Aula.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

	}
 ?>