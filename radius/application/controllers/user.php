<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$this->load->view('header');
		$this->load->view('user/user');
		$this->load->view('footer');
	}

	public function add()
	{
		$this->load->view('header');
		$this->load->view('user/add');
		$this->load->view('footer');
	}

	public function delete()
	{
		$this->load->view('header');
		$this->load->view('user/delete');
		$this->load->view('footer');
	}

	public function edit()
	{
		$this->load->view('header');
		$this->load->view('user/edit');
		$this->load->view('footer');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */