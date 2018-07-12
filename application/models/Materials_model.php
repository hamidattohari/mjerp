<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materials_model extends MY_Model {

	protected $_t = 'materials';
		
	var $table = 'materials';
	var $column = array('m.id','m.name', 'mc.name', 'min_stock', 'u.symbol'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('m.id as id, m.name as name, mc.name as category, min_stock, u.symbol as uom');
		$this->db->from($this->table.' m');
		$this->db->where('m.deleted',0);
		$this->db->join('material_categories mc', 'm.material_categories_id = mc.id', 'left');
		$this->db->join('uom u', 'm.uom_id = u.id', 'left');
 
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

	public function get_materials_per_vendor($id)
	{
		$this->db->select(array('id', 'name'));
		$this->db->from($this->table);
		$this->db->where('vendors_id', $id);
		return $this->db->get()->result();
	}

	public function populate_autocomplete(){
		$this->db->like('name', $this->input->get('term'), 'both');
		return $this->db->get($this->_t)->result();
	}

}
