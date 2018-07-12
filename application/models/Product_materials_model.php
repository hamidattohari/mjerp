<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_materials_model extends MY_Model {

	protected $_t = 'product_materials';

	function get_product_materials($id){
		$this->db->select('pm.id as id, pm.materials_id as materials_id, m.name as name, qty, symbol as unit');
		$this->db->where('products_id', $id);
		$this->db->join('materials m', 'pm.materials_id = m.id', 'left');
		$this->db->join('uom', 'm.uom_id = uom.id', 'left');
		$result = $this->db->get($this->_t.' pm');
		return $result->result();
	}

	function get_product_materials2($id){
		$allowed_material_ctg = array(-1); 
		$this->db->where('usage_categories_id', $this->input->get('usage_categories_id'));
		$result = $this->db->get('material_usage_categories')->result();
		foreach($result as $item){
			array_push($allowed_material_ctg, $item->material_categories_id);
		}

		$this->db->select(array('m.id as id','m.code as code','m.name as name','pm.qty as qty'));
		$this->db->where('products_id', $id);
		$this->db->like('m.name', $this->input->get('term'), 'both');
		$this->db->group_start();
			$this->db->where_in('m.material_categories_id', $allowed_material_ctg);
		$this->db->group_end();
		$this->db->join('materials m', 'pm.materials_id = m.id', 'left');
		$this->db->join('material_categories mc', 'm.material_categories_id = mc.id', 'left');
		$result = $this->db->get($this->_t.' pm');
		return $result->result();
	}

}
