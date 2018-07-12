<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Btkl_model extends MY_Model {

	protected $_t = 'btkl';
		
	var $table = 'btkl';
	var $column = array('id', 'qty','total', 'month', 'year'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('id, qty, total, motnh, year');
		$this->db->from($this->table);
		$this->db->where('deleted',0);
 
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

	public function get_btkl_list($id)
	{
		$this->db->where('hpp_id', $id);
		$result = $this->db->get($this->_t)->result();
		return $result;
	}

}
