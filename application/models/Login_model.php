<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    
    function  __construct() {
		parent::__construct();
			$this->load->database();
	}

	function check_login($username, $password){	
        $this->db->select('users.id as id, username, users.name as name, roles_id, roles.name as role, password');
		$this->db->from('users');
		$this->db->join('roles', "users.roles_id = roles.id");
        $this->db->where('username',$username);

        $result = $this->db->get();
        if($result->num_rows() == 1){
            if(password_verify($password, $result->row(0)->password)){
               $data['user_id'] = $result->row(0)->id;
               $data['username'] = $result->row(0)->username;
               $data['name'] = $result->row(0)->name;
               $data['role_id'] = $result->row(0)->roles_id;
               $data['role'] = $result->row(0)->role;
               return $data;
            }
        }else{
            return false;
        }
    }
}
