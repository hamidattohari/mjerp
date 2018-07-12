<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_process_model extends MY_Model {

	protected $_t = 'product_process';

	function get_product_process($id){
        $this->db->select(array('pp.id as id','processes_id', 'p.name as name'));
        $this->db->where('products_id', $id);
        $this->db->join('processes p', 'pp.processes_id = p.id', 'left');
        $result = $this->db->get($this->_t.' pp');
        return $result->result();
	}

}
