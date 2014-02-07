<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equipo_m extends MY_Model 
{
	public $name = NULL;
	private $id = NULL;
	public $member_1 = NULL;
	public $member_2 = NULL;
	
	public function get($long = NULL, $inicio = 0)
	{
		if($long != NULL)
			$this->db->limit($long, $inicio);
		$this->db->select("*");
		$this->db->order_by("t1.name asc");
		$this->db->from("team t1");
		$query = $this->db->get();
		//die($this->db->last_query());
		return $query->result();
	}

	public function getQualifies()
	{
		$query = $this->db->query("SELECT *, (SELECT count(*) FROM `team_match` ta where COALESCE((select COUNT(*) from score tx inner join game  ty on tx.id_game = ty.id where tx.open = 1 and ty.id_match = ta.id group by ty.id_match), 0) = 0 and (ta.id_team_1 = t1.id || ta.id_team_2 = t1.id)) as EJ,    (SELECT COUNT(*) from team_match t4 where (t4.id_team_1 = t1.id AND t4.team_1_score > t4.team_2_score) OR (t4.id_team_2 = t1.id AND t4.team_2_score > t4.team_1_score)) as EG, (SELECT count(*) FROM `team_match` ta where COALESCE((select COUNT(*) from score tx inner join game  ty on tx.id_game = ty.id where tx.open = 1 and ty.id_match = ta.id and tx.id_team = t1.id group by ty.id_match), 0) = 0) as JJ, (SELECT COALESCE(SUM(t2.team_1_score), 0) from team_match t2 where t2.id_team_1 = t1.id) + (SELECT COALESCE(SUM(t3.team_2_score), 0) from team_match t3 where t3.id_team_2 = t1.id) as JG,  COALESCE((SELECT SUM(score) from score t5 where t5.id_team = t1.id),0) as GF, COALESCE((SELECT SUM(score) from score t6 inner join game t7 on t6.id_game = t7.id inner join team_match t8 on t8.id = t7.id_match where (t8.id_team_1 = t1.id OR t8.id_team_2 = t1.id) AND (t6.id_team != t1.id)), 0) as GC from team t1 order by EG DESC, JG DESC, (GF - GC) DESC", FALSE);
		return $query->result();
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
			"name" => $this->name,
			"member_1" => $this->member_1,
			"member_2" => $this->member_2
		);
		if($this->db->insert("team", $datos)) {
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
		if($this->db->delete("team")) {
			return TRUE;
		}
		else {
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