<?php 

	class AulasModel extends Mysql
	{
		public $intIdaula;
		public $strAula;
		public $strNivel;
		public $intStatus;

		public function __construct()
		{
			parent::__construct();
		}

		public function selectAulas()
		{
			$whereAdmin = "";
			if($_SESSION['idUser'] != 1 ){
				$whereAdmin = " and idaula != 1 ";
			}
			//EXTRAE ROLES
			$sql = "SELECT * FROM aulas WHERE status != 0".$whereAdmin;
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectAula(int $idaula)
		{
			//BUSCAR Aula
			$this->intIdAula = $idaula;
			$sql = "SELECT * FROM aulas WHERE idaula = $this->intIdAula";
			$request = $this->select($sql);
			return $request;
		}

		public function insertAula(string $aula, string $nivel, int $status){

			$return = "";
			$this->strAula = $aula;
			$this->strNivel = $nivel;
			$this->intStatus = $status;

			$sql = "SELECT * FROM aulas WHERE nombreaula = '{$this->strAula}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO aulas(nombreaula,nivel,status) VALUES(?,?,?)";
	        	$arrData = array($this->strAula, $this->strNivel, $this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}	

		public function updateAula(int $idaula, string $aula, string $nivel, int $status){
			$this->intIdAula = $idaula;
			$this->strAula = $aula;
			$this->strNivel = $nivel;
			$this->intStatus = $status;

			$sql = "SELECT * FROM aulas WHERE nombreaula = '$this->strAula' AND idrol != $this->intIdAula";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE aulas SET nombreaula = ?, nivel = ?, status = ? WHERE idaula = $this->intIdAula ";
				$arrData = array($this->strAula, $this->strNivel, $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteAula(int $idaula)
		{
			$this->intIdAula = $idaula;
			$sql = "SELECT * FROM persona WHERE rolid = $this->intIdAula";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE aulas SET status = ? WHERE idaula = $this->intIdAula ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}
	}
 ?>