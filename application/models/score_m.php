<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Score_m extends MY_Model 
{
	private $id = NULL;
	public $id_game = NULL;
	public $id_team = NULL;
	public $score = NULL;
	
	public function get($long = NULL, $inicio = 0)
	{
		if($long != NULL)
			$this->db->limit($long, $inicio);
		$this->db->select("*");
		$this->db->from("score");
		$query = $this->db->get();
		//SELECT * 
		//FROM (`score` t1)  inner JOIN `game` t3 ON `t1`.`id_game` = `t3`.`id` 
		//inner JOIN `team_match` t4 ON `t3`.`id_match` = `t4`.`id` 
		//JOIN `team` t2 ON `t4`.`id_team_1` = t2.id 
		//inner join team t5 on t4.id_team_2 = t5.id GROUP BY t1.id_game
		$this->db->select("t1.id as score, t1.id_game as game, t3.id_match as team_match, t2.name as team_1, t5.name as team_2, t1.open as open, t2.id as team_1_id, t5.id as team_2_id", FALSE);
		$this->db->order_by("t3.id_match ASC, t1.id_game ASC, t1.id ASC");
		$this->db->from("score t1");
		$this->db->join("game t3", "t1.id_game = t3.id");
		$this->db->join("team_match t4", "t3.id_match = t4.id");
		$this->db->join("team t2", "t4.id_team_1 = t2.id");
		$this->db->join("team t5", "t4.id_team_2 = t5.id");
		$this->db->group_by("t1.id_game");
		$this->db->where("t1.open", 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function getByTeam($id_team)
	{
		$this->db->select("*");
		$this->db->from("score");
		$query = $this->db->get();
		//SELECT * 
		//FROM (`score` t1)  inner JOIN `game` t3 ON `t1`.`id_game` = `t3`.`id` 
		//inner JOIN `team_match` t4 ON `t3`.`id_match` = `t4`.`id` 
		//JOIN `team` t2 ON `t4`.`id_team_1` = t2.id 
		//inner join team t5 on t4.id_team_2 = t5.id GROUP BY t1.id_game
		$this->db->select("t1.id as score, t1.id_game as game, t3.id_match as team_match, t2.name as team_1, t5.name as team_2, t1.open as open, t2.id as team_1_id, t5.id as team_2_id", FALSE);
		$this->db->order_by("t3.id_match ASC, t1.id_game ASC, t1.id ASC");
		$this->db->from("score t1");
		$this->db->join("game t3", "t1.id_game = t3.id");
		$this->db->join("team_match t4", "t3.id_match = t4.id");
		$this->db->join("team t2", "t4.id_team_1 = t2.id");
		$this->db->join("team t5", "t4.id_team_2 = t5.id");
		$this->db->group_by("t1.id_game");
		$this->db->where("t1.open", 1);
		$this->db->where("(t2.id = '".$id_team."' OR t5.id = '".$id_team."')", NULL, FALSE);
		$query = $this->db->get();
		return $query->result();
	}

	public function update($datos) {	
		$this->db->where(array(
			"id_game" => $datos["game"],
			"id_team" => $datos["team1"]["id"]
		));

		$this->db->update("score", array(
			"score" => $datos["team1"]["score"],
			"open" => 0
		));

		$this->db->where(array(
			"id_game" => $datos["game"],
			"id_team" => $datos["team2"]["id"]
		));

		$this->db->update("score", array(
			"score" => $datos["team2"]["score"],
			"open" => 0
		));
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
			"id_game" => $this->id_game,
			"id_team" => $this->id_team,
			"score" => $this->score
		);
		if($this->db->insert("score", $datos)) {
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
		if($this->db->delete("score")) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function deleteByGame($id)
	{
		$this->db->where("id_game",$id);
		if($this->db->delete("score")) {
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