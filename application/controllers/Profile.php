<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->model('users_model', 'um');
	}

	public function index()
	{
		$data['title'] = "ERP | Profile";
		$data['page_title'] = "Profile";	
		$data['breadcumb']  = array("Profile");
		$data['page_view']  = "profile";		
		$data['js_asset']   = "profile";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();						
		$this->load->view('layouts/master', $data);
	}

	public function get_profile_details(){
		$data = $this->um->get_profile_details();
		echo json_encode($data);
	}

	public function update(){
		$password = $this->input->post('password');
		$user_data = $this->um->get_by_id('id', $this->session->userdata('user_id'));	
		if($password != $user_data->password){
			$password = password_hash($password, PASSWORD_BCRYPT);
		}
		$data = array(
			'name' => $this->normalize_text($this->input->post('name')),
			'email' => $this->input->post('email'),
			'password' => $password,
			'telp' => $this->input->post('telp'),
			'address' => $this->normalize_text($this->input->post('address'))
		);
		$status = $this->um->update('id', $this->session->userdata('user_id'), $data);
		echo json_encode(array('status' => $status));
	}


}
