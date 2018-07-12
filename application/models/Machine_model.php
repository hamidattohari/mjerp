<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Machine_model extends MY_Model {

	protected $_t = 'machine';
		
	var $table = 'machine';
	var $column = array('mc.id', 'code', 'processes_id'); //set column field database for order and search
    var $order = array('mc.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('mc.id, code, processes_id, p.name');
		$this->db->from($this->table." mc");
		$this->db->where('mc.deleted',0);
		$this->db->join("processes p", "mc.processes_id = p.id", "left");
 
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

	public function populate_select(){
		$this->db->select('mc.id as id, mc.code as code, p.name as name');
		$this->db->join("processes p", "mc.processes_id = p.id", "left");
		return $this->db->get($this->table." mc")->result();
	}

}
