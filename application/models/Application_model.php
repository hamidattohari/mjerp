<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_model extends MY_Model {

	protected $_t = 'company_info';
		
	var $table = 'company_info';
	
	public function get_info(){
		$this->db->select('ci.name as name, address, telp, logo_path, logo_title_path, taxpayer_reg_number, owner, currency_id, c.name as currency');
		$this->db->join('currency c', 'ci.currency_id = c.id', 'left');
		$this->db->where('ci.id', 1);
		$row = $this->db->get($this->_t.' ci')->row();
		return $row;
	}

	public function get_logo(){
		$this->db->select('logo_path, logo_title_path');
		$this->db->where('id', 1);
		$row = $this->db->get($this->_t)->row();
		return $row;
	}

}
