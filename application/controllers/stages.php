<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stages extends MY_Controller 
{
	protected $titulo = "Primera Fase";
	protected $modelo = "Score_m";
		
	public function index()
	{
		$datos = array();
		//$this->load->model("score_m");
		$datos["scores"] = $this->Score_m->get();
		$this->cargarPagina("score", $datos);
	}

	public function updateScore($id, $t1, $t2) {
		$cerrar = $this->input->post("cerrar");
		if($cerrar != 1) {
			$match = $this->input->post("match");
			$score1 = $this->input->post("team1");
			$score2 = $this->input->post("team2");
			$this->Score_m->update(array(
				"game" => $id,
				"team1" => array(
					"id" => $t1,
					"score" => $score1
				),
				"team2" => array(
					"id" => $t2,
					"score" => $score2
				)
			));
			$this->load->model("Match_m");
			$this->Match_m->update(array(
				"match_id" => $match,
				"team_1_score" => $score1 > $score2 ? 1 : 0,
				"team_2_score" => $score2 > $score1 ? 1 : 0
			));
		} else {
			$this->Score_m->deleteByGame($id);
		}
		redirect("stages");
	}

	public function teamGames($id) {
		$datos = array();
		$datos["scores"] = $this->Score_m->getByTeam($id);
		$this->cargarPagina("score", $datos);
	}
	
// 	public function pagina()
// 	{
// 		$i = $this->input->post("i");
// 		$empresas = $this->Empresa_m->get($this->pagina_tam, $i*$this->pagina_tam);
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$raiz->addAtributo("pagina", $i);
// 		$raiz->addAtributo("tamano", $this->pagina_tam);
// 		$raiz->addAtributo("mostrando", count($empresas));
// 		$raiz->addAtributo("total", $this->Empresa_m->getTotal());
// 		$i = ($i*$this->pagina_tam)+1;
// 		foreach($empresas as $empresa)
// 		{
// 			$nodo = $this->xmldocument->crearNodo("elemento");
// 			$nodo->addAtributo("id", $empresa->id);
// 			$nodo->addAtributo("n", $i);
// 			$nodo->addElemento($this->xmldocument->crearNodo("nombre", $empresa->nombre));
// 			$nodo->addElemento($this->xmldocument->crearNodo("imagen", $empresa->thumbnail));
// 			$nodo->addElemento($this->xmldocument->crearNodo("categoria", $empresa->categoria));
// 			$nodo->addElemento($this->xmldocument->crearNodo("rating", $empresa->rating));
// 			$nodo->addElemento($this->xmldocument->crearNodo("votos", $empresa->votos));
// 			$nodo->addElemento($this->xmldocument->crearNodo("beneficios", $empresa->beneficios));
// 			$nodo->addElemento($this->xmldocument->crearNodo("url", $empresa->url));
// 			$nodo->addElemento($this->xmldocument->crearNodo("estado", $empresa->estado));
// 			$raiz->addElemento($nodo);
// 			$i++;
// 		}
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
	
// 	public function paginaABC()
// 	{
// 		$i = $this->input->post("i");
// 		$empresas = $this->Empresa_m->getABC($i);
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$raiz->addAtributo("pagina", $i);
// 		$i = 1;
// 		foreach($empresas as $empresa)
// 		{
// 			$nodo = $this->xmldocument->crearNodo("elemento");
// 			$nodo->addAtributo("id", $empresa->id);
// 			$nodo->addAtributo("n", $i);
// 			$nodo->addElemento($this->xmldocument->crearNodo("nombre", $empresa->nombre));
// 			$nodo->addElemento($this->xmldocument->crearNodo("imagen", $empresa->thumbnail));
// 			$nodo->addElemento($this->xmldocument->crearNodo("categoria", $empresa->categoria));
// 			$nodo->addElemento($this->xmldocument->crearNodo("rating", $empresa->rating));
// 			$nodo->addElemento($this->xmldocument->crearNodo("votos", $empresa->votos));
// 			$nodo->addElemento($this->xmldocument->crearNodo("beneficios", $empresa->beneficios));
// 			$nodo->addElemento($this->xmldocument->crearNodo("url", $empresa->url));
// 			$nodo->addElemento($this->xmldocument->crearNodo("estado", $empresa->estado));
// 			$raiz->addElemento($nodo);
// 			$i++;
// 		}
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
// 	
// 	
	private function createResults($game_id, $teamA, $teamB) {
		$this->load->model("Score_m");
		$this->Score_m->id_game = $game_id;
		$this->Score_m->id_team = $teamA;
		$score_id = $this->Score_m->guardar();
		if($score_id === FALSE) {
			return FALSE;
		} else {
			$this->Score_m->id_team = $teamB;
			return $score_id = $this->Score_m->guardar() ? TRUE : FALSE;
		}
		return TRUE;
	}

	private function createGames($match_id, $teamA, $teamB) {
		$this->load->model("Game_m");
		for($i = 0; $i < 5; $i++) {
			$this->Game_m->id_match = $match_id;
			$game_id = $this->Game_m->guardar();
			if($game_id === FALSE) {
				return FALSE;
			} else {
				if($this->createResults($game_id, $teamA, $teamB) === FALSE) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

 	private function createMatches($target_id) {
		$this->load->model("Match_m");
		$equipos = $this->Equipo_m->get();
		for($i = 0; $i < count($equipos); $i++) {
			if($equipos[$i]->id !== $target_id) {
				$this->Match_m->id_team_1 = $equipos[$i]->id;
				$this->Match_m->id_team_2 = $target_id;
				$match_id = $this->Match_m->guardar();
				if($match_id === FALSE) {
					return FALSE;
				} else {
					if($this->createGames($match_id, $equipos[$i]->id, $target_id) === FALSE) {
						return FALSE;
					}
				}
			}
		}
		return TRUE;
 	}
	
	public function newEquipo()
	{
		$this->titulo = 'Anadir Nuevo Equipo';
		$datos = array();
		$this->load->library("form_validation");
		
		$this->form_validation->set_rules("name", "Nombre", "trim|required|max_length[50]");
		$this->form_validation->set_rules("member_1", "Miembro #1", "trim");
		$this->form_validation->set_rules("member_2", "Miembro #2", "trim|max_length[300]");
		
		if($this->input->post("postback")==1)
		{
			if($this->form_validation->run()) {
				$this->load->helper("security");
				
				$this->Equipo_m->name = set_value("name");
				$this->Equipo_m->member_1 = set_value("member_1");
				$this->Equipo_m->member_2 = set_value("member_2");
				$the_id = $this->Equipo_m->guardar();
				if($the_id !== FALSE && $this->createMatches($the_id)) {
					redirect("equipos");	
				} else {
					if($the_id) {
						$this->Equipo_m->eliminar($the_id);
					}
				}
			}
		}
		$this->cargarPagina("equipos_nuevo", $datos);
	}
	
// 	public function editar($id)
// 	{
// 		$datos = array();
// 		$this->load->model("CMS/Categorias_m");
// 		$this->load->library("form_validation");
		
// 		$this->form_validation->set_rules("nombre", "Nombre", "trim|required|max_length[50]");
// 		$this->form_validation->set_rules("categoria", "Categoría", "trim");
// 		$this->form_validation->set_rules("url", "URL", "trim|max_length[300]");
// 		$this->form_validation->set_rules("descripcion", "Descripcion", "trim|xss_clean|max_length[1000]");
// 		$this->form_validation->set_rules("informacion", "Información", "trim|xss_clean");
// 		$this->form_validation->set_rules("imagen_actual", "ImagenActual", "trim|xss_clean");
// 		$this->form_validation->set_rules("tmb_actual", "tmbActual", "trim|xss_clean");
// 		$this->form_validation->set_rules("c_imagen", "Imagen", "callback_procesarCImagen");
		
// 		if($this->input->post("postback")!=1)
// 		{
// 			$empresa = $this->Empresa_m->getById($id);
// 			if(!$empresa)
// 				show_404();
// 			$datos['empresa'] = $empresa;
// 		}
// 		else
// 		{
// 			$_descripcion = strip_tags($_POST["descripcion"], "<iframe><b><i><br><p><a><ul><ol><li><img>");
// 			$_informacion = strip_tags($_POST["informacion"], "<iframe><b><i><br><p><a><ul><ol><li><img>");
// 			if($this->form_validation->run())
// 			{
// 				$this->load->helper("security");
				
// 				$this->Empresa_m->nombre = set_value("nombre");
// 				$this->Empresa_m->categoria = set_value("categoria");
// 				$this->Empresa_m->descripcion = $_descripcion;//xss_clean($this->input->post("descripcion"));//set_value("descripcion");
// 				$this->Empresa_m->informacion = $_informacion;//xss_clean($this->input->post("informacion"));//set_value("informacion");
// 				$this->Empresa_m->imagen = ($this->c_img)?$this->imagen:set_value("imagen_actual");
// 				$this->Empresa_m->url = set_value("url");
// 				$this->Empresa_m->thumbnail = ($this->c_img)?$this->thumbnail:set_value("tmb_actual");
// 				if($this->Empresa_m->actualizar($id))
// 				{
					
// 					//die("hl".$this->Empresa_m->descripcion);
// 					if($this->c_img)
// 					{
// 						@unlink("./img/empresas/".set_value("imagen_actual"));
// 						@unlink("./img/empresas/".set_value("tmb_actual"));
// 					}
// 					redirect("CMS/empresas");
// 				}
// 			}
// 		}
// 		$datos['categorias'] = $this->Categorias_m->get();
		
// 		$this->cargarPagina("empresas_editar", $datos);
// 	}
	
// 	public function procesarImagen($str)
// 	{
// 		$datos = $this->subirArchivo('imagen', './img/empresas/');
// 		if(!$datos)
// 		{
// 			$this->form_validation->set_message("procesarImagen",implode("<br>",$this->error));
// 			return false;
// 		}
// 		if(!$this->redimensionarImg($datos["full_path"],112, 84, "auto", TRUE))
// 		{
// 			$this->form_validation->set_message("procesarImagen",implode("<br>",$this->error));
// 			$this->borrarArchivos();
// 			return false;
// 		}
// 		$this->imagen = $datos["file_name"];
// 		if(!$this->redimensionarImg($datos["full_path"],345, 345, "auto"))
// 		{
// 			$this->form_validation->set_message("procesarImagen",implode("<br>",$this->error));
// 			$this->borrarArchivos();
// 			return false;
// 		}
// 		$this->thumbnail = $datos["raw_name"]."_thumb".$datos["file_ext"];
// 		return true;	
// 	}
	
// 	public function procesarCImagen($str)
// 	{
// 		if($str != 1)
// 			return TRUE;
// 		$this->c_img = TRUE;
// 		$datos = $this->subirArchivo('imagen', './img/empresas/');
// 		if(!$datos)
// 		{
// 			$this->form_validation->set_message("procesarImagen",implode("<br>",$this->error));
// 			return false;
// 		}
// 		if(!$this->redimensionarImg($datos["full_path"],112, 84, "auto", TRUE))
// 		{
// 			$this->form_validation->set_message("procesarImagen",implode("<br>",$this->error));
// 			$this->borrarArchivos();
// 			return false;
// 		}
// 		$this->imagen = $datos["file_name"];
// 		if(!$this->redimensionarImg($datos["full_path"],345, 345, "auto"))
// 		{
// 			$this->form_validation->set_message("procesarImagen",implode("<br>",$this->error));
// 			$this->borrarArchivos();
// 			return false;
// 		}
// 		$this->thumbnail = $datos["raw_name"]."_thumb".$datos["file_ext"];
// 		return true;
// 	}
	
// 	public function activar()
// 	{
// 		$id = $this->input->post("id");
// 		$i = $this->input->post("i");
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$raiz->addAtributo("id", $id);
		
// 		if($this->Empresa_m->activar($id, $i))
// 			$raiz->addAtributo("r",1);
// 		else
// 			$raiz->addAtributo("r",0);
		
// 		$raiz->addAtributo("estado",$i);
		
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
	
	public function quitar($id)
	{		
		if($this->Equipo_m->eliminar($id)) {
			redirect("equipos");
		}
	}
	
// 	public function beneficios()
// 	{
// 		$this->load->library("XmlDocument");
// 		$id = $this->input->post("id");
// 		$beneficios = $this->Empresa_m->getBeneficiosCiudad($id);
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$actual = NULL;
// 		foreach($beneficios as $beneficio)
// 		{
// 			if($beneficio->id != $actual)
// 			{
// 				$nodo = $this->xmldocument->crearNodo("beneficio");
// 				$nodo->addAtributo("id", $beneficio->id);
// 				$nodo->addElemento($this->xmldocument->crearNodo("descripcion", $beneficio->descripcion));
// 				$nodo->addElemento($this->xmldocument->crearNodo("estado", $beneficio->estado));
// 				$actual = $beneficio->id;
// 				$raiz->addElemento($nodo);
// 			}
// 			$nodo->addElemento($this->xmldocument->crearNodo("ciudad", $beneficio->sigla));
// 		}
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
	
// 	public function addBeneficio()
// 	{
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$id = $this->input->post("id");
// 		$ciudades = $this->input->post("ciudad");
// 		$siglas = $this->input->post("sigla");
// 		$descripcion = $this->input->post("descripcion");
// 		$raiz->addAtributo("id",$id);
// 		if(!$this->Empresa_m->guardarBeneficio($id, $descripcion, $ciudades))
// 			$raiz->addAtributo("r",0);
// 		else
// 		{
// 			$raiz->addAtributo("r",1);
// 			$nodo = $this->xmldocument->crearNodo("descripcion",$descripcion);
// 			$raiz->addElemento($nodo);
// 			$i = 0;
// 			for($i=0; $i < count($ciudades); $i++)
// 				$raiz->addElemento($this->xmldocument->crearNodo("ciudad",$siglas[$i]));
// 		}
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
	
// 	public function eliminarBeneficio()
// 	{
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$id = $this->input->post("id");
// 		$raiz->addAtributo("id",$id);
// 		if($this->Empresa_m->eliminarBeneficio($id))
// 			$raiz->addAtributo("r",1);
// 		else
// 			$raiz->addAtributo("r",0);
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
	
// 	public function editBeneficio()
// 	{
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$id = $this->input->post("id");
// 		$raiz->addAtributo("id",$id);
// 		$anadir = $this->input->post("anadir");
// 		$quitar = $this->input->post("quitar");
// 		$descripcion = $this->input->post("descripcion");
// 		if($this->Empresa_m->editarBeneficio($id, $descripcion, $anadir, $quitar))
// 			$raiz->addAtributo("r",1);
// 		else
// 			$raiz->addAtributo("r",0);
// 		$raiz->addElemento($this->xmldocument->crearNodo("descripcion",$descripcion));
// 		//foreach($anadir as $add)
// //			$raiz->addElemento($this->xmldocument->crearNodo("anadir",$add));
// //		foreach($quitar as $rmv)
// //			$raiz->addElemento($this->xmldocument->crearNodo("remover",$rmv));
			
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
	
// 	public function estadoBeneficio()
// 	{
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$id = $this->input->post("id");
// 		$raiz->addAtributo("id",$id);
// 		$estado = $this->input->post("i");
// 		if($this->Empresa_m->estadoBeneficio($id, $estado))
// 			$raiz->addAtributo("r",1);
// 		else
// 			$raiz->addAtributo("r",0);
// 		$raiz->addAtributo("estado", $estado);
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();
// 	}
	
// 	public function addCiudad()
// 	{
// 		$this->load->library("XmlDocument");
// 		$raiz = $this->xmldocument->crearNodo("root");
// 		$nombre = $this->input->post("nombre");
// 		$sigla = $this->input->post("sigla");
// 		if($this->Empresa_m->addCiudad($nombre, $sigla))
// 		{
// 			$raiz->addAtributo("r",1);
// 			$this->load->model("Parametros_m");
// 			$ciudades = $this->Parametros_m->getCiudades();
// 			foreach($ciudades as $ciudad)
// 			{
// 				$nodo = $this->xmldocument->crearNodo("ciudad");
// 				$nodo->addAtributo("id",$ciudad->id);
// 				$nodo->addElemento($this->xmldocument->crearNodo("nombre", $ciudad->nombre));
// 				$nodo->addElemento($this->xmldocument->crearNodo("sigla", $ciudad->sigla));
// 				$raiz->addElemento($nodo);
// 			}
// 		}
// 		else
// 			$raiz->addAtributo("r",0);
// 		$this->xmldocument->setRaiz($raiz);
// 		$this->xmldocument->imprimirXML();	
// 	}
	
// 	private function subirArchivo($src, $path, $types = 'gif|jpg|png')
// 	{
// 		$config['upload_path'] = $path;
// 		$config['allowed_types'] = $types;
// 		$config['encrypt_name'] = true;
// 		//$config['max_size']	= '100';
// 		$this->load->library('upload');
// 		$this->upload->initialize($config);
// 		if(!$this->upload->do_upload($src))
// 		{
// 			array_push($this->error, $this->upload->display_errors());
// 			return false;
// 		}
// 		else
// 			$datos = $this->upload->data();
// 		array_push($this->archivos,$datos["full_path"]);	
// 		return $datos;
// 	}
	
// 	private function redimensionarImg($img,$w,$h,$dim=NULL,$thumb = FALSE, $thumb_mkr = "_thumb"){
// 		ini_set("memory_limit","20M");
// 		$this->load->library("image_lib");
// 		$this->image_lib->clear();
// 		unset($config);
// 		$config["image_library"]='gd2';
// 		$config["source_image"]= $img;
// 		$config["maintain_ratio"] = TRUE;
// 		$config["create_thumb"] = $thumb;
// 		$config['width'] = $w;
// 		$config['height'] = $h;
// 		$config['master_dim'] = ($dim==NULL)?'auto':$dim;
// 		$config['thumb_marker'] = $thumb_mkr;
		
// 		$this->image_lib->initialize($config);
// 		if($this->image_lib->resize())
// 			return TRUE;
// 		array_push($this->error = $this->image_lib->display_errors());
// 		return FALSE;
// 	}
	
// 	private function borrarArchivos()
// 	{
// 		foreach($this->archivos as $archivo)
// 			@unlink($archivo);
// 		$this->archivos = array();
// 	}
}
?>