<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_usage_cat_model extends MY_Model {

	protected $_t = 'material_usage_categories';
		
	var $table = 'material_usage_categories';
	var $column = array('id','material_categories_id', 'usage_categories_id'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('muc.id as id','mc.name as name', 'usage_categories_id');
		$this->db->join('material_categories mc','muc.material_categories_id = mc.id', 'left');
		$this->db->from($this->table. " muc");
 
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

	function get_material_usage_categories($id){
        $this->db->select(array('muc.id as id','mc.id as name','usage_categories_id'));
        $this->db->where('usage_categories_id', $id);
        $this->db->join('material_categories mc', 'mc.id = muc.material_categories_id', 'LEFT');
        $result = $this->db->get('material_usage_categories muc');
        return $result->result();
	}

}
