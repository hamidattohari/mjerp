<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_model extends CI_Model {
    
    function  __construct() {
		parent::__construct();
			$this->load->database();
	}

	function check_login($username, $password){	
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username',$username);

        $result = $this->db->get();
        if($result->num_rows() == 1){
            if(password_verify($password, $result->row(0)->password)){
               $data['username'] = $result->row(0)->username;
               $data['name'] = $result->row(0)->name;
               return $data;
            }
        }else{
            return false;
        }
    }
}
