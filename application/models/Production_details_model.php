<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_details_model extends MY_Model {

	protected $_t = 'production_details';

	function get_production_details($id){
		$this->db->select('pd.id as id, production_date, p.code as project_code, wo.id as wo_id');
		$this->db->where('productions_id', $id);
		$this->db->join('productions pc', 'pd.productions_id = pc.id', 'left');
		$this->db->join('work_orders wo', 'pd.work_orders_id = wo.id', 'left');
		$this->db->join('project_details prd', 'wo.project_details_id = prd.id', 'left');
		$this->db->join('projects p', 'prd.projects_id = p.id', 'left');		
		$result = $this->db->get($this->_t.' pd');
		return $result->result();
	}

	function populate_product_select($id){
		$this->db->select('pd.id as id, p.name as value');
		$this->db->where('projects_id', $id);	
		$this->db->join('products p', 'pd.products_id = p.id', 'left');
		$result = $this->db->get($this->_t.' pd');
		return $result->result();
	}

	public function populate_production_det_select($id)
	{
		$this->db->select('pd.id as production_details_id, p.id as products_id, p.name as value');
		// $this->db->where('pd.id', $id);
		$this->db->join('work_orders wo', 'pd.work_orders_id = wo.id', 'left');
		$this->db->join('project_details prd', 'wo.project_details_id = prd.id', 'left');
		$this->db->join('products p', 'prd.products_id = p.id', 'left');		
		$result = $this->db->get($this->_t.' pd');
		return $result->result();
	}

}
