<?php 
	const BASE_URL = "http://localhost/colegio";
	//const BASE_URL = "https://elvisruizdev.com";

	//Zona horaria
	date_default_timezone_set('America/Lima');

	//Datos de conexión a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "pruebacole";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_PORT = "3306";
	const DB_CHARSET = "utf8";

	//Para envío de correo
	const ENVIRONMENT = 1; // Local: 0, Produccón: 1;

	//Deliminadores decimal y millar Ej. 24,1989.00
	const SPD = ".";
	const SPM = ",";

	//Simbolo de moneda
	const SMONEY = "S/.";
	const CURRENCY = "PEN";

	
	//Datos envio de correo
	const NOMBRE_REMITENTE = "I.E LOPEZ ALBUJAR";
	const EMAIL_REMITENTE = "no-reply@elvisruiz.com";
	const NOMBRE_EMPESA = "I.E LOPEZ ALBUJAR";
	const WEB_EMPRESA = "www.elvisruiz.com";

	const DESCRIPCION = "I.E Lopez Albujar";
	const SHAREDHASH = "LopezAlbujar";

	//Datos Empresa
	const DIRECCION = "JN PIURA";
	const TELEMPRESA = "999999999";
	const WHATSAPP = "+99999999";
	const EMAIL_EMPRESA = "info@elvisruiz.com";
	const EMAIL_PEDIDOS = "info@elvisruiz.com"; 
	const EMAIL_SUSCRIPCION = "info@elvisruiz.com";
	const EMAIL_CONTACTO = "info@elvisruiz.com";

	const CAT_SLIDER = "1,2,3";
	const CAT_BANNER = "4,5,6";
	const CAT_FOOTER = "1,2,3,4,5";

	//Datos para Encriptar / Desencriptar
	const KEY = 'elvisruiz';
	const METHODENCRIPT = "AES-128-ECB";

	
	//Módulos
	const MDASHBOARD = 1;
	const MUSUARIOS = 2;
	const MCLIENTES = 3;
	const MPRODUCTOS = 4;
	const MPEDIDOS = 5;
	const MJUSTIFICACION = 6;
	const MSUSCRIPTORES = 7;
	const MDCONTACTOS = 8;
	const MDPAGINAS = 9;

	//Páginas
	const PINICIO = 1;
	const PTIENDA = 2;
	const PCARRITO = 3;
	const PNOSOTROS = 4;
	const PCONTACTO = 5;
	const PPREGUNTAS = 6;
	const PTERMINOS = 7;
	const PSUCURSALES = 8;
	const PERROR = 9;

	//Roles
	const RADMINISTRADOR = 1;
	const RSUPERVISOR = 2;
	const RCLIENTES = 3;

	const STATUS = array('Completo','Aprobado','Cancelado','Reembolsado','Pendiente','Entregado');

	//Productos por página
	const CANTPORDHOME = 8;
	const PROPORPAGINA = 4;
	const PROCATEGORIA = 4;
	const PROBUSCAR = 4;

	//REDES SOCIALES
	const FACEBOOK = "https://www.facebook.com/elvisruiz";
	const INSTAGRAM = "https://www.instagram.com/nn/";
	

 ?>