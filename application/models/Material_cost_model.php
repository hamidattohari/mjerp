<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_cost_model extends MY_Model {

	protected $_t = 'material_cost';
		
	var $table = 'material_cost';
	var $column = array('mc.id'); //set column field database for order and search
    var $order = array('mc.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('mc.id as id, mc.materials_id as materials_id, mc.price, mc.hpp_id as hpp_id');
		$this->db->from($this->_t.' mc');
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

	public function get_material_cost($id, $wo_id)
	{
		$this->db->where('id', $id);
		$hpp = $this->db->get('hpp')->row();

		if(!isset($hpp)){
			return array();
		}
	
		$this->db->select('mud.materials_id as id,  m.name as name, SUM(qty_pick) as `pick`, SUM(qty_return) as `return`, u.symbol, mc.id as idcat,mc.name as category');
		$this->db->join('material_usages mu', 'mud.material_usages_id = mu.id', 'left');
		$this->db->join('materials m', 'mud.materials_id = m.id', 'left');
		$this->db->join('uom u', 'm.uom_id = u.id', 'left');
		$this->db->join('material_categories mc', 'm.material_categories_id = mc.id', 'left');
		$this->db->join('work_orders wo', 'mu.work_orders_id = wo.id', 'left');
		$this->db->where('mu.products_id', $hpp->products_id);
		$this->db->where('wo.id', $wo_id);
		$this->db->group_by('mud.materials_id');
		$this->db->order_by('mc.id', 'asc');
		$result = $this->db->get('material_usages_detail mud')->result();
		return $result;
	}

	public function get_material_list($id, $wo_id)
	{
		$this->db->where('id', $id);
		$hpp = $this->db->get('hpp')->row();

		if(!isset($hpp)){
			return array();
		}
	
		$this->db->select('mud.materials_id as id,  m.name as name, SUM(qty_pick) as `pick`, SUM(qty_return) as `return`, u.symbol, mc.id as idcat,mc.name as category,mcs.price as price');
		$this->db->join('material_usages mu', 'mud.material_usages_id = mu.id', 'left');
		$this->db->join('materials m', 'mud.materials_id = m.id', 'left');
		$this->db->join('material_cost mcs', 'm.id = mcs.materials_id', 'left');
		$this->db->join('uom u', 'm.uom_id = u.id', 'left');
		$this->db->join('material_categories mc', 'm.material_categories_id = mc.id', 'left');
		$this->db->join('work_orders wo', 'mu.work_orders_id = wo.id', 'left');
		$this->db->where('mu.products_id', $hpp->products_id);
		$this->db->where('mcs.hpp_id', $hpp->id);
		$this->db->where('wo.id', $wo_id);
		$this->db->group_by('mud.materials_id');
		$this->db->order_by('mc.id', 'asc');
		$result = $this->db->get('material_usages_detail mud')->result();
		return $result;
	}

}
