<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_vendor_model extends MY_Model {

	protected $_t = 'material_vendor';
		
	public function get_material_vendor($material_id)
	{
		$this->db->select('mv.id as id, v.id as vendors_id, v.name, v.address, v.telp');		
		$this->db->join('vendors v', 'mv.vendors_id = v.id', 'left');		
		$this->db->where('materials_id', $material_id );		
		$result = $this->db->get($this->_t.' mv')->result();
		return $result;
	}

	public function get_vendor_material($vendor_id)
	{
		$this->db->select('mv.id as id, m.id as materials_id, m.name as name, mc.name as category, min_stock, uom.symbol as symbol');		
		$this->db->join('materials m', 'mv.materials_id = m.id', 'left');		
		$this->db->join('material_categories mc', 'm.material_categories_id = mc.id', 'left');		
		$this->db->join('uom', 'm.uom_id = uom.id', 'left');		
		$this->db->where('vendors_id', $vendor_id );		
		$result = $this->db->get($this->_t.' mv')->result();
		return $result;
	}

	public function populate_autocomplete_material()
	{
		$this->db->select('m.id as materials_id, m.name as name');		
		$this->db->join('materials m', 'mv.materials_id = m.id', 'left');		
		$this->db->where('vendors_id', $this->input->get('vendors_id') );		
		$result = $this->db->get($this->_t.' mv')->result();
		return $result;
	}


}
