<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */