<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check extends CI_Controller {

	public function index()
	{
		 if($this->session->userdata('member_id')){
				

		 }else{
			redirect(base_url()."index.php/auth/login");
		 }
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */