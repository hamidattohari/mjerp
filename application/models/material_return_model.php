<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_return_model extends MY_Model {

	protected $_t = 'material_usages';
		
	var $table = 'material_usages';
	var $column = array('mu.id','mu.date', 'code_pick', 'code_return', 'wo.code', 'p.name'); //set column field database for order and search
    var $order = array('mu.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('mu.id as id, mu.date as date, code_pick, code_return, wo.code as wocode, p.name as name');
		$this->db->from($this->_t.' mu');
		$this->db->where('mu.material',1);
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

	public function have_material_return($a, $id)
	{		
		$this->db->select(array('id','return_date','code'));
		$this->db->where($a, $id);
        $result = $this->db->get($this->table);
        $data = array();
        if($result->result()){
            $data = $result->row();
        }
        return $data;
	}

	public function generate_id($id){
		$this->db->select('material_usages_id');
		$this->db->where('id', $id);
		$row = $this->db->get('material_usages_detail')->row();

		$this->db->select('code_return');
		$this->db->where('id', $row->material_usages_id);
		$row2 = $this->db->get('material_usages')->row();

		if($row2->code_return == ""){
			$this->db->select("MAX(`code_return`) as 'maxID'");
			$result = $this->db->get($this->_t);
			$code = $result->row(0)->maxID;
			$code++; 
			$this->db->where('id', $row->material_usages_id);
			$this->db->update('material_usages', array('code_return' => sprintf("%08s", $code)));
		}
	}

	public function get_material_return($id)
	{
		$this->db->select('mu.id as id, mu.date as date, code_pick, code_return, wo.code as wocode, p.name as name, uc.name as usage_categories');
		$this->db->from($this->_t.' mu');
		$this->db->join('work_orders wo', 'mu.work_orders_id = wo.id', 'left');
		$this->db->join('machine m', 'mu.machine_id = m.id', 'left');
		$this->db->join('products p', 'mu.products_id = p.id', 'left');
		$this->db->join('usage_categories uc', 'mu.usage_categories_id = uc.id', 'left');
		$this->db->where('mu.id', $id);
		return $this->db->get()->row();
	}

}
