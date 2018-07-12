<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nonmaterial_usage_model extends MY_Model {

	protected $_t = 'material_usages';
		
	var $table = 'material_usages';
	var $column = array('mu.id','mu.date', 'mu.code_pick', 'wo.code', 'p.name'); //set column field database for order and search
    var $order = array('mu.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('mu.id as id, mu.date as date, code_pick, code_return, wo.code as wocode, p.name as name');
		$this->db->from($this->_t.' mu');
		$this->db->where('mu.material',0);
		$this->db->join('work_orders wo', 'mu.work_orders_id = wo.id', 'left');
		$this->db->join('machine m', 'mu.machine_id = m.id', 'left');
		$this->db->join('products p', 'mu.products_id = p.id', 'left');
 
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

	public function generate_pick_id(){
		$prefix = "PNM/";
		$this->db->select("MAX(RIGHT(`code_pick`, 7)) as 'maxID'");
		$this->db->like('code_pick', $prefix, 'after');
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return $prefix.sprintf("%07s", $code);
	}

	public function generate_return_id(){
		$prefix = "RNM/";
		$this->db->select("MAX(RIGHT(`code_pick`, 7)) as 'maxID'");
		$this->db->like('code_pick', $prefix, 'after');
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return $prefix.sprintf("%07s", $code);
	}

	public function get_material_usage($id)
	{
		$this->db->select('mu.id as id, mu.date as date, mu.code_pick as code, wo.code as wocode, p.name as name, uc.name as usage_categories');
		$this->db->from($this->_t.' mu');
		$this->db->join('work_orders wo', 'mu.work_orders_id = wo.id', 'left');
		$this->db->join('machine m', 'mu.machine_id = m.id', 'left');
		$this->db->join('products p', 'mu.products_id = p.id', 'left');
		$this->db->join('usage_categories uc', 'mu.usage_categories_id = uc.id', 'left');
		$this->db->where('mu.id', $id);
		return $this->db->get()->row();
	}

}
