<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $titulo = NULL;
	protected $modelo = NULL;
	protected $error = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("login_m");
		$this->load->library("XmlDocument");
		// if(!$this->login_m->checkSession())
		// {
		// 	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){	
		// 		$root = $this->xmldocument->crearNodo("root");
		// 		$root->addAtributo("r", "0");
		// 		$root->setValor("Su sesión ha expirado, inicie sesión de nuevo");
		// 		$this->xmldocument->setRaiz($root);
		// 		$this->xmldocument->imprimirXML();
		// 		die();
		// 	}elseif(isset($_SERVER['CONTENT_TYPE']) && !((string)strpos($_SERVER['CONTENT_TYPE'],"multipart/form-data; boundary=")===FALSE)){
		// 		die('<script type="text/javascript">alert("Su Sesión ha expirado, inicie sesión de nuevo.\n\nEn unos segundpos será redireccionado a la pantalla de inicio de sesión");  document.write("Si no es redireccionado clickee <a href=\"'.site_url('CMS').'\">acá</a>"); location.href="'.site_url().'";</script><body></body>');
		// 	}else{
		// 		redirect("home");
		// 	}
		// }
		if($this->modelo != NULL)
			$this->load->model($this->modelo);
	}
		
	public function index()
	{
		//$this->cargarPagina("club");
		
	}
	
	protected function cargarPagina($pagina, $datos = NULL)
	{
		$this->load->model("Equipo_m");
		$equiposList = $this->Equipo_m->get();
		$datos = array(
			"titulo" => $this->titulo,
			"pagina" => $pagina,
			"equiposList" => $equiposList,
			"datos" => $datos
		);
		$this->load->view("pagina", $datos);
	}
	
}
?>