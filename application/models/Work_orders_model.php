<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Work_orders_model extends MY_Model {

	protected $_t = 'work_orders';
	
	var $table = 'work_orders';
	var $column = array('wo.id', 'wo.id', 'wo.code', 'start_date', 'end_date', 'p.code', 'wo.ppn'); //set column field database for order and search
    var $order = array('wo.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('wo.id as id, wo.code as code, start_date, end_date, wo.ppn as ppn, p.code as projects_code');
		$this->db->from($this->table.' wo');
		$this->db->join('projects p', 'wo.projects_id = p.id', 'left');
 
		$i = 0;
	 
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		 
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_work_orders($id){
        $this->db->select('wo.id as id, pd.id as pd_id, wo.code as code, start_date, end_date, wo.qty as qty');
        $this->db->where('projects_id', $id);
		$this->db->join('project_details pd', 'wo.project_details_id = pd.id', 'left');
        $this->db->join('projects p', 'pd.projects_id = p.id', 'left');		
        $result = $this->db->get($this->_t.' wo');
        return $result->result();
	}

	function populate_wo_select(){
		$this->db->select('wo.id as id, wo.code as wo_code, p.code as p_code');
		$this->db->join('projects p', 'wo.projects_id = p.id', 'left');	
		$result = $this->db->get($this->_t.' wo');
        return $result->result();
	}

	
	public function generate_id(){
		$this->db->where('id', $this->input->get('projects_id'));
		$row = $this->db->get('projects')->row();
		$vat = "P";
		if($row->vat == 0){
			$vat = "NP";
		}
		$seg1 = "/MC-".$vat; 
		$seg2 = "/MKT-".$this->get_roman_number(date("n")); 
		$seg3 = "/".date("Y"); 
		$this->db->select("MAX(LEFT(`code`, 3)) as 'maxID'");
        $this->db->like('code', $seg1.$seg2.$seg3, 'before');
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return sprintf("%03s", $code).$seg1.$seg2.$seg3;
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

	public function populate_month_year($type)
	{
		if($type == "month"){
			$this->db->select("DISTINCT(MONTH(start_date)) as id");
		}else if($type == "year"){
			$this->db->select("DISTINCT(YEAR(start_date)) as id");
		}	
		$result = $this->db->get($this->_t);
		return $result->result();
	}

}
