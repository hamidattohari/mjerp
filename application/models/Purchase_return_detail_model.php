<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return_detail_model extends MY_Model {

	protected $_t = 'p_return_details';
		
	var $table = 'p_return_details';
	var $column = array('ps.id', 'ps.code', 'shipping_date', 'note', 'p.code'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('ps.id as id, ps.code as code, shipping_date, note, p.code as p_code');
		$this->db->from($this->table.' ps');
		$this->db->join('projects p', 'ps.projects_id = p.id', 'left');
 
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

	public function generate_id(){
		$prefix = "SH-";
		$infix = date("Ymd-");
		$this->db->select("MAX(RIGHT(`code`, 4)) as 'maxID'");
        $this->db->like('code', $prefix.$infix, 'after');
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return $prefix.$infix.sprintf("%04s", $code);
	}

	function get_purchase_return_details($id){
		$this->db->select('prd.id as id, qty, prd.note as note, prd.materials_id as materials_id');
		$this->db->where('return_id', $id);
		$this->db->join('materials m', 'prd.materials_id = m.id', 'left');
		$result = $this->db->get($this->_t.' prd');
		return $result->result();
	}

}
