<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Work_order_detail_model extends MY_Model {

	protected $_t = 'work_order_detail';

	var $table = 'work_order_detail';

	function add_wo_details($id_wo, $details){
		$data = array();
		foreach($details as $item){
			$row = array();
			$row['qty'] = $item->qty;
			$row['products_id'] = $item->products_id;
			$row['work_orders_id'] = $id_wo;
			$row['note'] = $item->note;
			$data[] = $row;
		}
		return $this->db->insert_batch($this->_t, $data);
	}

	function get_work_order_details($id){
        $this->db->select('wod.id as id, p.name as name, wod.qty as qty, u.symbol as symbol, products_id as pid, work_orders_id as woid, wod.note as note, p.code as pcode');		
        $this->db->where('wod.work_orders_id', $id);		
        $this->db->join('products p', 'wod.products_id = p.id', 'left');		
        $this->db->join('uom u', 'p.uom_id = u.id', 'left');		
        $result = $this->db->get($this->_t.' wod');
        return $result->result();
	}

	function populate_wo_select(){
		$this->db->select('wo.id as id, wo.code as wo_code, p.code as p_code');
		$this->db->join('projects p', 'wo.projects_id = p.id', 'left');	
		$result = $this->db->get($this->_t.' wo');
        return $result->result();
	}
	
	function get_detail($id){
        $this->db->select('wo.id as id, wo.code as code, start_date, end_date, wo.projects_id as projects_id, p.code as projects_code');
        $this->db->where('wo.id', $id);
        $this->db->join('projects p', 'wo.projects_id = p.id', 'left');		
        $result = $this->db->get($this->_t.' wo')->row();
        return $result;
	}

	public function populate_autocomplete(){
		$this->db->like('code', $this->input->get('term'), 'both');
		return $this->db->get($this->_t)->result();
	}

	public function populate_product_select($id){
		$this->db->select('p.id as id, p.code as code, p.name as name');		
        $this->db->where('wod.work_orders_id', $id);		
        $this->db->join('products p', 'wod.products_id = p.id', 'left');		
        $result = $this->db->get($this->_t.' wod');
        return $result->result();
	}

	public function get_work_orders_by_month_year()
	{
		$year = $this->input->get('year');	
		$month = $this->input->get('month');

		if(sizeof($month) == 1){
			$month = "0".$month;
		}
		
		$this->db->select('DISTINCT wo.id as id, wo.code as code', FALSE);
		$this->db->join('work_orders wo', 'wd.work_orders_id = wo.id', 'left');
		$this->db->where('DATE_FORMAT(wo.start_date, "%Y-%m") = ', $year."-".$month);
		$result = $this->db->get($this->_t.' wd');
		return $result->result();
	}

	public function get_product_by_month_year($wo_id)
	{
		$year = $this->input->get('year');	
		$month = $this->input->get('month');

		if(sizeof($month) == 1){
			$month = "0".$month;
		}
		
		$this->db->select('DISTINCT p.id as id, p.code as code, p.name as name', FALSE);
		$this->db->join('work_orders w', 'wd.work_orders_id = w.id', 'left');
		$this->db->join('products p', 'wd.products_id = p.id', 'left');
		$this->db->where('DATE_FORMAT(w.start_date, "%Y-%m") = ', $year."-".$month);
		$this->db->where('wd.work_orders_id', $wo_id);
		$result = $this->db->get($this->_t.' wd');
		return $result->result();
	}

}
