<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_movement_model extends MY_Model {

	protected $_t = 'product_movement';
		
	var $table = 'product_movement';
	var $column = array('id','work_orders_id', 'products_id'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('pm.id as id, p.name as name, pm.created_at as time');
		$this->db->join('work_orders wo', 'wo.id = pm.work_orders_id', 'left');
		$this->db->join('products p', 'p.id = pm.products_id', 'left');
		$this->db->from($this->table." pm");
 
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

	public function get_data(){
		return $this->db->get('roles')->result();
	}

	public function get_product_movement($woid, $pid, $prid)
	{
		$this->db->select('pm.created_by, pm.id as id, pmd.code as code, pmd.date');
		$this->db->from('product_movement pm');
		$this->db->join('product_movement_details pmd', 'pm.id = pmd.product_movement_id', 'left');
		$this->db->where('pm.work_orders_id', $woid);
		$this->db->where('pmd.processes_id', $prid);
		$this->db->where('pm.products_id', $pid);
		$this->db->group_by('pm.id');
		return $this->db->get()->result();
	}

	public function count_product_movement($woid, $pid)
	{
		$this->db->from('product_movement');
		$this->db->where('work_orders_id', $woid);
		$this->db->where('products_id', $pid);
		return $this->db->count_all_results();
	}
}
