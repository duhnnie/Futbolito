<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Match_m extends MY_Model 
{
	private $id = NULL;
	public $id_team_1 = NULL;
	public $id_team_2 = NULL;
	public $team_1_score = NULL;
	public $team_2_score = NULL;
	
	public function get($long = NULL, $inicio = 0)
	{
		if($long != NULL)
			$this->db->limit($long, $inicio);
		$this->db->select("*");
		$this->db->from("team_match");
		$query = $this->db->get();
		//die($this->db->last_query());
		return $query->result();
	}

	public function getById($id)
	{
		$this->db->select("*");
		$this->db->from("team_match");
		$this->db->where("id", $id);
		$query = $this->db->get();
		//die($this->db->last_query());
		return $query->result()[0];
	}
	
	// public function getABC($inicial)
	// {
	// 	if(!(strlen($inicial)==1 && ((ord($inicial)>=65 &&  ord($inicial)<=90) || ord($inicial)==35)))
	// 		return FALSE;
	// 	$this->db->select("t1.id, t1.nombre, t1.url, t1.estado, t1.thumbnail, t2.nombre as categoria, COALESCE(ROUND(AVG(t3.calificacion),2),0) as rating, COUNT(*) as votos, (select count(*) from beneficio tx where tx.id_empresa = t1.id) as beneficios", FALSE);
	// 	$this->db->order_by("t1.nombre asc");
	// 	$this->db->from("empresa t1");
	// 	$this->db->join("categoria t2", "t1.id_categoria = t2.id");
	// 	$this->db->join("voto t3", "t1.id = t3.id_empresa", "left");
	// 	if(ord($inicial)==35)
	// 		$this->db->where("t1.nombre NOT REGEXP '^[[:alpha:]]'");
	// 	else
	// 		$this->db->like("t1.nombre", $inicial, "after");
	// 	$this->db->group_by("t1.id");
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }
	
	// public function getTotal()
	// {
	// 	return $this->db->count_all("empresa");
	// }
	
	public function guardar()
	{
		$datos = array(
			"id_team_1" => $this->id_team_1,
			"id_team_2" => $this->id_team_2
		);
		if($this->db->insert("team_match", $datos)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	// public function getById($id)
	// {
	// 	$this->db->where("id",$id);
	// 	$res = $this->db->get("empresa");
	// 	if(!$res)
	// 		return FALSE;
	// 	if($res->num_rows()==0)
	// 		return FALSE;
	// 	else
	// 		$res = $res->result();
	// 	return $res[0];
	// }
	// 
	
	public function update($datos)
	{
		$id = $datos["match_id"];
		$current = $this->getById($id);
		var_dump($current);
		$s1 = intval($current->team_1_score);
		$s2 = intval($current->team_2_score);
		$datos = array(
			"team_1_score" => $datos["team_1_score"] + $s1,
			"team_2_score" => $datos["team_2_score"] + $s2,
		);
		$this->db->where("id", $id);
		return $this->db->update("team_match",$datos);
	}
	
	// public function actualizar($id)
	// {
	// 	$datos = array(
	// 		"id_categoria" => $this->categoria,
	// 		"nombre" => $this->nombre,
	// 		"descripcion" => $this->descripcion,
	// 		"informacion" => $this->informacion,
	// 		"imagen" => $this->imagen,
	// 		"thumbnail" => $this->thumbnail,
	// 		"url" => $this->url
	// 	);
	// 	$this->db->where("id", $id);
	// 	return $this->db->update("empresa",$datos);
	// }
	
	// public function activar($id, $i)
	// {
	// 	$this->db->where("id",$id);
	// 	return $this->db->update("empresa",array("estado"=>$i));
	// }
	
	public function eliminar($id)
	{
		$this->db->where("id",$id);
		if($this->db->delete("team_match")) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// private function getArchivos($id)
	// {
	// 	$this->db->select("imagen, thumbnail");
	// 	$this->db->where("id", $id);
	// 	$res = $this->db->get("empresa");
	// 	$res = $res->result();
	// 	return $res[0];
	// }
	
	// public function getBeneficiosCiudad($id)
	// {
	// 	$this->db->select("t1.descripcion, t1.id, t2.id_ciudad, t3.nombre as ciudad, t3.sigla, t1.estado");
	// 	$this->db->from("beneficio t1");
	// 	$this->db->join("beneficio_ciudad t2", "t1.id = t2.id_beneficio", "left");
	// 	$this->db->join("ciudad t3", "t3.id = t2.id_ciudad");
	// 	$this->db->where("t1.id_empresa",$id);
	// 	$this->db->order_by("t1.id asc, t3.sigla asc");
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }
	
	// public function guardarBeneficio($id, $descripcion, $ciudades)
	// {
	// 	if(!$this->db->insert("beneficio",array("id_empresa"=>$id, "descripcion"=>$descripcion)))
	// 		return FALSE;
	// 	$id_beneficio = $this->db->insert_id();
	// 	foreach($ciudades as $ciudad)
	// 		$this->db->insert("beneficio_ciudad",array("id_beneficio"=>$id_beneficio, "id_ciudad"=>$ciudad));
	// 	return TRUE;
	// }
	
	// public function editarBeneficio($id, $descripcion, $anadir, $quitar)
	// {
	// 	$this->db->where("id",$id);
	// 	if(!$this->db->update("beneficio", array("descripcion"=>$descripcion)))
	// 		return FALSE;
	// 	if(is_array($anadir))
	// 		foreach($anadir as $ciudad)
	// 			if(!$this->db->insert("beneficio_ciudad", array("id_beneficio"=>$id, "id_ciudad"=>$ciudad)))
	// 				return FALSE;
	// 	if(is_array($quitar))
	// 		foreach($quitar as $ciudad)
	// 		{
	// 			$this->db->where("id_ciudad",$ciudad);
	// 			$this->db->where("id_beneficio",$id);
	// 			if(!$this->db->delete("beneficio_ciudad"))
	// 				return FALSE;
	// 		}
	// 	return TRUE;
	// }
	
	// public function eliminarBeneficio($id)
	// {
	// 	$this->db->where("id", $id);
	// 	if(!$this->db->delete("beneficio"))
	// 		return FALSE;
	// 	$this->db->where("id_beneficio",$id);
	// 	$this->db->delete("beneficio_ciudad");
	// 	return TRUE;
	// }
	
	// public function estadoBeneficio($id, $estado)
	// {
	// 	$this->db->where("id", $id);
	// 	if($this->db->update("beneficio", array("estado"=>$estado)))
	// 		return TRUE;
	// 	else
	// 		return FALSE;
	// }
	
	// public function addCiudad($nombre, $sigla)
	// {
	// 	$this->load->helper("security");
	// 	$nombre = trim(xss_clean($nombre));
	// 	$sigla = strtoupper(trim(xss_clean($sigla)));
	// 	if(!$this->db->query("INSERT IGNORE into ciudad (nombre, sigla) values ('$nombre', '$sigla')"))
	// 		return FALSE;
	// 	if($this->db->affected_rows()==1)
	// 		return TRUE;
	// 	else
	// 		return FALSE;
	// }
}
?>