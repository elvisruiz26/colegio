<?php 
class DocentesModel extends Mysql
{
	private $intIdUsuario;
	private $strIdentificacion;
	private $strNombre;
	private $strApellido;
	private $intCodigoAsistencia;
	private $intTelefono;
	private $strEmail;
	private $strPassword;
	private $strToken;
	private $intTipoId;
	private $intStatus;
	private $intWhatsapp;
	private $strFechaNac;
	private $strDirUser;

	public function __construct()
	{
		parent::__construct();
	}	

	public function insertDocente(string $identificacion, string $nombre, string $apellido, int $intcodigoasistencia, int $telefono, string $email, string $password, int $tipoid, string $whatsapp, string $fechanac, string $dirUser){

		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intCodigoAsistencia = $intcodigoasistencia;
		$this->intTelefono = $telefono;
		$this->strEmail = $email;
		$this->strPassword = $password;
		$this->intTipoId = $tipoid;
		$this->intWhatsapp = $whatsapp;
		$this->strFechaNac = $fechanac;
		$this->strDirUser = $dirUser;

		$return = 0;
		$sql = "SELECT * FROM persona WHERE 
				email_user = '{$this->strEmail}' or identificacion = '{$this->strIdentificacion}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO persona(identificacion,nombres,apellidos,codigoasistencia,telefono,email_user,password,rolid,whatsapp,fecha_nac,direccionuser) 
							  VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        	$arrData = array($this->strIdentificacion,
    						$this->strNombre,
    						$this->strApellido,
							$this->intCodigoAsistencia,
    						$this->intTelefono,
    						$this->strEmail,
    						$this->strPassword,
    						$this->intTipoId,
    						$this->intWhatsapp,
    						$this->strFechaNac,
    						$this->strDirUser);
        	$request_insert = $this->insert($query_insert,$arrData);
        	$return = $request_insert;
		}else{
			$return = "exist";
		}
        return $return;
	}

	public function selectDocentes()
	{
		$sql = "SELECT idpersona,identificacion,nombres,apellidos,codigoasistencia,telefono,email_user,status 
				FROM persona 
				WHERE rolid = ".RCLIENTES." and status != 0 "; 
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectDocente(int $idpersona){
		$this->intIdUsuario = $idpersona;
		$sql = "SELECT idpersona,identificacion,nombres,apellidos,codigoasistencia,telefono,email_user,whatsapp,DATE_FORMAT(fecha_nac, '%d-%m-%Y') as fecha_nac,direccionuser,status, DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro 
				FROM persona
				WHERE idpersona = $this->intIdUsuario and rolid = ".RCLIENTES;
		$request = $this->select($sql);
		return $request;
	}

	public function updateDocente(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $intcodigoasistencia,int $telefono, string $email, string $password, string $whatsapp, string $fechanac, string $dirUser){

		$this->intIdUsuario = $idUsuario;
		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intCodigoAsistencia = $intcodigoasistencia;
		$this->intTelefono = $telefono;
		$this->strEmail = $email;
		$this->strPassword = $password;
		$this->intWhatsapp = $whatsapp;
		$this->strFechaNac = $fechanac;
		$this->strDirUser = $dirUser;

		$sql = "SELECT * FROM persona WHERE (email_user = '{$this->strEmail}' AND idpersona != $this->intIdUsuario)
									  OR (identificacion = '{$this->strIdentificacion}' AND idpersona != $this->intIdUsuario) ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			if($this->strPassword  != "")
			{
				$sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, codigoasistencia=?, telefono=?, email_user=?, password=?, whatsapp=?, fecha_nac=?, direccionuser=? 
						WHERE idpersona = $this->intIdUsuario ";
				$arrData = array($this->strIdentificacion,
        						$this->strNombre,
        						$this->strApellido,
								$this->intCodigoAsistencia,
        						$this->intTelefono,
        						$this->strEmail,
        						$this->strPassword,
        						$this->intWhatsapp,
        						$this->strFechaNac,
        						$this->strDirUser);
			}else{
				$sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, codigoasistencia=?, telefono=?, email_user=?, whatsapp=?, fecha_nac=?, direccionuser=? 
						WHERE idpersona = $this->intIdUsuario ";
				$arrData = array($this->strIdentificacion,
        						$this->strNombre,
        						$this->strApellido,
								$this->intCodigoAsistencia,
        						$this->intTelefono,
        						$this->strEmail,
        						$this->intWhatsapp,
        						$this->strFechaNac,
        						$this->strDirUser);
			}
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
		return $request;
	}

	public function deleteDocente(int $intIdpersona)
	{
		$this->intIdUsuario = $intIdpersona;
		$sql = "DELETE persona WHERE idpersona = $this->intIdUsuario ";
		return $request;
	}

	
}

 ?>