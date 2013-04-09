<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	public function index()
	{
		 if($this->session->userdata('username'))redirect(base_url()."index.php");
		 else redirect(base_url()."index.php/auth/login");
	}

	public function check()
	{
		 if($this->session->userdata('username')){
				

		 }else{
			redirect(base_url()."index.php/auth/login");
		 }
		
	}

	public function login()
	{	
		 
		 $data['message'] ="";

		 if($this->session->userdata('username'))redirect(base_url()."index.php");



		if($post=$this->input->post()){
			extract($post);
			$sql = "select * from administrator where username ='$user' and password=md5('$password')";
			$ret = $this->db->query($sql);
			if($ret->num_rows()){
				$data_ret=$ret->result();
				$this->session->set_userdata('username',$data_ret[0]->username);

				$sql = "UPDATE `administrator` SET `login`=1,`lastlogin`=now() WHERE username='$user'";
				$this->db->query($sql);
				
			
				redirect(base_url()."index.php");

			}else{
			  $data['message'] = "Wrong user name or password";
			}
		}

		$this->load->view('login',$data);
	}

		
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url()."index.php/auth/login");
	}
		
}