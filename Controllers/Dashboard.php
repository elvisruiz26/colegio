<?php 

	class Dashboard extends Controllers{
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
			getPermisos(MDASHBOARD);
		}

		public function dashboard()
		{
			$data['page_id'] = 2;
			$data['page_tag'] = "Dashboard - Tienda Virtual";
			$data['page_title'] = "Dashboard - Tienda Virtual";
			$data['page_name'] = "dashboard";
			$data['page_functions_js'] = "functions_dashboard.js";
			$data['usuarios'] = $this->model->cantUsuarios();
			$data['clientes'] = $this->model->cantClientes();
			$data['productos'] = $this->model->cantProductos();
			$data['pedidos'] = $this->model->cantPedidos();
			$data['pedidos'] = $this->model->cantPedidos();
			$data['lastOrders'] = $this->model->lastOrders();
			$data['productosTen'] = $this->model->productosTen();

			$anio = date('Y');
			$mes = date('m');
			$data['pagosMes'] = $this->model->selectPagosMes($anio,$mes);
			//dep($data['pagosMes']);exit;
			$data['ventasMDia'] = $this->model->selectVentasMes($anio,$mes);
			//dep($data['ventasMDia']);exit;
			$data['ventasAnio'] = $this->model->selectVentasAnio($anio);
			//dep($data['ventasAnio']);exit;

			$data['asistenciasMes'] = $this->model->getAsistenciasMes($anio, $mes);
			$data['faltasMes'] = $this->model->getFaltasMes($anio, $mes);
			$data['tardanzasMes'] = $this->model->getTardanzasMes($anio, $mes);
			$data['observacionesMes'] = $this->model->getObservacionesMes($anio, $mes);
			if( $_SESSION['userData']['idrol'] == RCLIENTES ){
				$this->views->getView($this,"dashboardCliente",$data);
			}else{
				$this->views->getView($this,"dashboard",$data);
			}
		}

		public function tipoPagoMes(){
			if($_POST){
				$grafica = "tipoPagoMes";
				$nFecha = str_replace(" ","",$_POST['fecha']);
				$arrFecha = explode('-',$nFecha);
				$mes = $arrFecha[0];
				$anio = $arrFecha[1];
				$pagos = $this->model->selectPagosMes($anio,$mes);
				$script = getFile("Template/Modals/graficas",$pagos);
				echo $script;
				die();
			}
		}
		public function ventasMes(){
			if($_POST){
				$grafica = "ventasMes";
				$nFecha = str_replace(" ","",$_POST['fecha']);
				$arrFecha = explode('-',$nFecha);
				$mes = $arrFecha[0];
				$anio = $arrFecha[1];
				$pagos = $this->model->selectVentasMes($anio,$mes);
				$script = getFile("Template/Modals/graficas",$pagos);
				echo $script;
				die();
			}
		}
		public function ventasAnio(){
			if($_POST){
				$grafica = "ventasAnio";
				$anio = intval($_POST['anio']);
				$pagos = $this->model->selectVentasAnio($anio);
				$script = getFile("Template/Modals/graficas",$pagos);
				echo $script;
				die();
			}
		}

		public function reporteAsistenciasAnual()
		{
			if($_POST){
				$anio = intval($_POST['anio']);
				$data['anio'] = $anio;
				$data['asistencias'] = $this->model->getAsistenciasAnual($anio);
				$this->views->getView($this, "reporteAsistenciasAnual", $data);
			}
		}

		public function reporteAsistenciasMes()
		{
			if($_POST){
				$nFecha = str_replace(" ","",$_POST['fecha']);
				$arrFecha = explode('-', $nFecha);
				$mes = $arrFecha[0];
				$anio = $arrFecha[1];
				$data['anio'] = $anio;
				$data['mes'] = $this->getMesNombre($mes);
				$data['asistencias'] = $this->model->getAsistenciasMes($anio, $mes);
				$this->views->getView($this, "reporteAsistenciasMes", $data);
			}
		}

		private function getMesNombre($mesNumero)
		{
			$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
			return $meses[$mesNumero - 1];
		}

		public function getReporteAsistenciasMes()
		{
			if($_POST){
				$mes = intval($_POST['mes']);
				$anio = intval($_POST['anio']);
				$data = $this->model->getAsistenciasPorMes($mes, $anio);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
				die();
			}
		}

		public function getReporteAsistenciasAnual()
		{
			if($_POST){
				$anio = intval($_POST['anio']);
				$data = $this->model->getAsistenciasPorAnio($anio);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
				die();
			}
		}

	}
 ?>