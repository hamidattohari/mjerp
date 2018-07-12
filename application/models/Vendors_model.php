<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors_model extends MY_Model {

	protected $_t = 'vendors';
		
	var $table = 'vendors';
	var $column = array('id','name', 'description', 'address', 'telp', 'ppn'); //set column field database for order and search
    var $order = array('name' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('id, name, description, address, telp, ppn');
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

	public function get_vendor_materials($id)
	{
		$this->db->select(array('m.id as id','v.name as name', 'm.name as material_name', 'm.id as materials_id', 'mc.id as material_category', 'min_stock','u.id as uom'));
		$this->db->where('v.id', $id);
		$this->db->from($this->table." v");
		$this->db->join('materials m', 'm.vendors_id = v.id', 'right');
		$this->db->join('material_categories mc', 'm.material_categories_id = mc.id', 'right');
		$this->db->join('uom u', 'm.uom_id = u.id', 'left');
		return $this->db->get()->result();
	}

	public function populate_autocomplete(){
		$this->db->like('name', $this->input->get('term'), 'both');
		return $this->db->get($this->_t)->result();
	}

	public function populate_autocomplete2(){
		$this->db->where('ppn', $this->input->get('vat'));
		$this->db->like('name', $this->input->get('term'), 'both');
		return $this->db->get($this->_t)->result();
	}


}
