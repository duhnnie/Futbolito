<?

class Login_m extends CI_Model
{
	private $username = NULL;
	private $password = NULL;
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library("session");
	}
	
	function setUsername($username)
	{
		$this->username =$username;
	}
	
	function setPassword($password)
	{
		$this->password =$password;
	}
	
	function checkSession(){
		$this->db->where("username",(string)$this->session->userdata("cms_username"));
		$this->db->where("codigo",(string)$this->session->userdata("cms_codigo"));
		$this->db->where("estado",1);
		
		$num = $this->db->count_all_results("usuario");
				
		if($num== 1)
		{
			$this->session->set_userdata("cms_username", (string)$this->session->userdata("cms_username"));
			$this->session->set_userdata("cms_codigo", (string)$this->session->userdata("cms_codigo"));
			return TRUE;
		}
		else
			return FALSE;
	}
	
	public function validarLogin()
	{		
		if($this->password == NULL || $this->username == NULL)
			return FALSE;
		
		$this->db->where("username",$this->username);
		$this->db->where("password",$this->password);
		$this->db->where("estado",1);
		$num = $this->db->count_all_results("usuario");
		if($num == 1)
			return TRUE;
		else
			return FALSE;
	}
	
	function run()
	{
		$this->load->library("MisFunciones");
		$this->session->sess_destroy();
		$res = array("resultado"=>FALSE, "alias"=>NULL, "codigo"=>NULL);
		
		if($this->validarLogin())
		{
			$codigo = md5(date("YmdHis").MisFunciones::generaNumero(3));
			$this->db->where("username",$this->username);
			$this->db->where("password",$this->password);
			$this->db->update("usuario",array("codigo"=>$codigo));
			if($this->db->affected_rows()==1)
			{
				//$this->load->model("Administrador_m");
				//$acceso = $this->Administrador_m->getAcceso($res["alias"]);
				//$acceso = explode(",",$acceso);
				$res = array("resultado"=>TRUE, "username"=>$this->username, "codigo"=>$codigo);
				$this->session->set_userdata("cms_codigo",$res["codigo"]);
				$this->session->set_userdata("cms_username",$res["username"]);
				//$this->session->set_userdata("cms_acceso",$acceso);
			}
		}
		return $res;
	}
	
	function logout(){
		$this->db->where("username",$this->session->userdata("cms_username"));
		$this->db->where("codigo",$this->session->userdata("cms_codigo"));
		$this->session->set_userdata("cms_username","");
		$this->session->set_userdata("cms_codigo","");
		//$this->session->set_userdata("cms_acceso","");
		$this->session->sess_destroy();
		if($this->db->update("usuario",array("codigo"=>"")))
			return TRUE;
		else
			return false;
	}
	
	function getAdministradorByEmail($email){
		$this->db->where("email",$email);
		$query = $this->db->get("usuario");
		if($query->num_rows()!=1)
			return FALSE;
		$query = $query->result();
		return $query[0];
	}
	
	function recuperarPassword($email){
		//1, no existe tal email
		//0 hay mas de uno con ese email
		$this->db->where("email", $email);
		$res = $this->db->get("usuario");
		if($res->num_rows()==0)
			return 1;
		else if($res->num_rows()>1)
			return 0;
		
		$this->load->helper('string');
		$codigo = md5(date("iHsmYd").random_string('alnum',3));
		$datos = array(
			"peticion" => $codigo,
			"peticion_caduca" => 'TIMESTAMPADD(HOUR,1,NOW())'
		);
		$query = $this->db->query("update usuario set peticion= '".$codigo."', peticion_caduca=TIMESTAMPADD(HOUR,1,NOW()) where email = '$email'");
		if($this->db->affected_rows()!=1)
			return FALSE;
			
		$datos = $this->getAdministradorByEmail($email);
		if($this->enviarEmailPassword($email,$datos->nombre,$codigo))
			return TRUE;
		else
			return FALSE;
	}
	
	private function anularPeticion($id){
		$datos=array(
			"peticion" => NULL,
			"peticion_caduca" => NULL
		);
		$this->db->where("id",$id);
		$this->db->update("usuario",$datos);
	}
	
	private function actualizarPassword($id, $password)
	{
		$datos = array("password"=>md5($password));
		$this->db->where("id",$id);
		if($this->db->update("usuario",$datos))
			return TRUE;
		else
			return FALSE;
	}

	function actualizarPasswordActual($password)
	{
		$datos = array("password"=>$password);
		$this->db->where("codigo",$this->session->userdata("cms_codigo"));
		$this->db->where("username",$this->session->userdata("cms_username"));
		$this->db->where("estado",1);
		$n = $this->db->update("usuario",$datos);
		if($n)
			return TRUE;
		else
			return FALSE;
	}
	
	function enviarNuevoPassword($hash,&$msg){
		$this->load->helper('string');
		$this->db->where("peticion",$hash);
		$this->db->where("peticion_caduca >","NOW()",FALSE);
		$this->db->where("estado",1);
		$query = $this->db->get("usuario");
		if($query->num_rows()!=1 || $hash == NULL)
		{
			$msg = "Petición caducada";
			return FALSE;
		}
		$query = $query->result();
		$query = $query[0];
		$email = $query->email;
		$alias = $query->username;
		$nombre = $query->nombre;
		$password = random_string('alnum',8);
		
		$this->anularPeticion($query->id);
		
		if(!$this->actualizarPassword($query->id, $password))
		{
			$msg = "No se pudo completa la operación, inténtelo de nuevo";
			return FALSE;
		}
		
		if($this->enviarDatosCuenta($email,$alias,$password,$nombre))
			return TRUE;
		else
			$msg = "No se puedo completar la operación, inténtelo de nuevo";
		return FALSE;
	}
	
	function getLogeado(){
		$this->db->from("usuario");
		$this->db->where("codigo",$this->session->userdata("cms_codigo"));
		$this->db->where("username",$this->session->userdata("cms_username"));
		$this->db->where("estado",1);
		$query = $this->db->get();
		if($query->num_rows!=1)
			return FALSE;
		else
			$query = $query->result();
		return $query[0];
	}

	function actualizarEmailActual($email)
	{
		$datos = array("email"=>$email);
		$this->db->where("codigo",$this->session->userdata("cms_codigo"));
		$this->db->where("username",$this->session->userdata("cms_usuario"));
		$this->db->where("estado",1);
		$n = $this->db->update("usuario",$datos);
		if($n)
			return TRUE;
		else
			return FALSE;
	}
	
	private function enviarEmailPassword($email, $nombre, $codigo){
		$mensaje = '
		========================================================================
		==== ÉSTE ES UN MENSAJE GENERADO AUTOMÁTICAMENTE. NO LO RESPONDAS.  ====
		========================================================================
		Hola, '.$nombre.'
		
		Has solicitado el reestablecimiento de tu contraseña de tu cuenta de
		administrador, para confirmar el cambio de password haz click en el 
		siguiente enlace o copialo y pégalo	en la barra de direcciones de tu 
		explorador.
		
		SI TU NO SOLICITASTE EL CAMBIO DE CONTRASENA IGNORA Y ELIMINA ESTE
		MENSAJE.
		
		'.base_url().'CMS/password/'.$codigo.'
		
		No compartas tus datos de cuenta con nadie. Elimina este e-mail después
		de leerlo.
		
		========================================================================
		======================== PROTEGE TU CONTRASEÑA =========================
		========================================================================
		
		NUNCA se te pedirá tu contraseña por correo electrónico. Solo necesitarás introducirla cuando inicies sesión. Asegurate que dirección del explorador comience exactamente con '.base_url().'.';
		
		return $this->enviarEmail($email,"Confirmación de reestablecimiento de Contraseña",$mensaje);
	}
	
	private function enviarDatosCuenta($email, $alias, $password, $nombre){
		$mensaje = '
		========================================================================
		==== ÉSTE ES UN MENSAJE GENERADO AUTOMÁTICAMENTE. NO LO RESPONDAS.  ====
		========================================================================
		Hola, '.$nombre.', sus datos de cuenta:
		
		Nombre de usuario:
		'.$alias.'
		
		Contrasena:
		'.$password.'
		
		========================================================================
		======================== PROTEGE TU CONTRASEÑA =========================
		========================================================================
		
		NUNCA se te pedirá tu contraseña por correo electrónico. Solo necesitarás introducirla cuando inicies sesión. Asegurate que dirección del explorador comience exactamente con '.base_url().'.';
		return $this->enviarEmail($email,"Datos de Cuenta de usuario en SAFI MSC Club",$mensaje);
	}
	
	private function enviarEmail($email,$asunto,$mensaje)
	{
		$this->load->library('email',NULL,'email2');
		$this->email2->from('no.reply@'.$this->config->item("site_domain"), 'SAFI MSC Club');
		$this->email2->to($email); 
		
		$this->email2->subject($asunto);
		$this->email2->message($mensaje);	
		
		if($this->email2->send())
			return TRUE;
		else
		{
			return FALSE;
		}
	}
	
}


?>