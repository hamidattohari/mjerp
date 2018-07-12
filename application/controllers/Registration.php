<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->model('registration_model', 'rm');
			$this->load->model('menu_model', 'mm');
			$this->load->model('users_model', 'um');			
			$this->load->model('roles_model', 'rom');			
			$this->load->library('session');
			//$this->lang->load('registration', 'english');
	}
                
	public function index()
	{	
		$this->mm->add_initial_menu();
		if($this->rom->count_all_data() == 0){
			$data = array(
				'name' => "Admin"
			);
			$inserted = $this->rom->add($data);
		}

		if($this->session->userdata('loggedIn')){
			redirect('dashboard');
		}elseif($this->um->count_all_data() > 0){
			$this->load->view('registration_info');
		}else{
			$data['csrf'] = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);	
			$data['title'] = "ERP | Application Registration";
			$data['roles'] = $this->rom->get_all_data();
			$this->load->view('authentication/registration', $data);
		}
	}

	function add(){
		$data = array(
			'id' => 1,
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
			'name' => $this->input->post('name'),
			'roles_id' => $this->input->post('roles'),
			'created_at' => date("Y-m-d H:m:s")
		);
		$inserted = $this->um->add($data);
		if($inserted){
			redirect('login');
		}else{
			$this->session->set_flashdata('registration_error', 'TRUE');
			redirect('registration');
		}
	}
}
