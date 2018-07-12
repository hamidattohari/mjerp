<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_table_model extends MY_Model {

	protected $_t = 'time_table';
		
	var $table = 'time_table';
	var $column = array('tt.id','tt.processes_id','tt.wo_id','tt.products_id','tt.date','tt.time_start','tt.time_end'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('tt.id as id, p.id as pid, wo.id as wo_id, pr.id as prid, tt.time_start, tt.time_end');
		$this->db->from($this->table.' tt');
		$this->db->join('work_orders wo', 'tt.wo_id = wo.id', 'left');
		$this->db->join('processes p', 'tt.processes_id = p.id', 'left');
		$this->db->join('products pr', 'tt.products_id = pr.id', 'left');
 
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

	public function get_time_table($woid, $pid, $prid)
	{
		$this->db->select('tt.id as id, p.id as pid, wo.id as wo_id, pr.id as prid, tt.date, tt.time_start, tt.time_end, tt.note as note');
		$this->db->from($this->table.' tt');
		$this->db->join('work_orders wo', 'tt.wo_id = wo.id', 'left');
		$this->db->join('processes p', 'tt.processes_id = p.id', 'left');
		$this->db->join('products pr', 'tt.products_id = pr.id', 'left');
		$this->db->where('tt.wo_id', $woid);
		$this->db->where('tt.processes_id', $pid);
		$this->db->where('tt.products_id', $prid);
		return $this->db->get()->result();
	}

}
