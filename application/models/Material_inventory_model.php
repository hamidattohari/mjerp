<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_inventory_model extends MY_Model {

	protected $_t = 'material_inventory';
		
	var $table = 'material_inventory';
	var $column = array('m.id', 'mc.name', 'm.name','m.name', 'm.name', 'm.name'); //set column field database for order and search
    var $order = array('m.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {

		$this->db->select('SUM(CASE WHEN mi1.type = "in" THEN mi1.qty ELSE 0 END)+m.initial_qty-SUM(CASE WHEN mi1.type = "out" THEN mi1.qty ELSE 0 END)');
		$this->db->from($this->table. " mi1");
		$this->db->where('YEAR(mi1.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(mi1.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
		$this->db->where('mi1.id = mi.id');
		$res = $this->db->get_compiled_select();

		$this->db->select('SUM(rd.total_price)-SUM(rd.qty)');
		$res1 = $this->db->get_compiled_select();
         
		$this->db->select(array('m.id as id', 'mc.name as category',
			'SUM(CASE WHEN mi.type = "in" THEN mi.qty ELSE 0 END)+m.initial_qty as debit', 
			'SUM(CASE WHEN mi.type = "out" THEN mi.qty ELSE 0 END) as credit',
			'SUM(CASE WHEN mi.type = "in" THEN mi.qty ELSE 0 END)+m.initial_qty-SUM(CASE WHEN mi.type = "out" THEN mi.qty ELSE 0 END) as qty',
			'IFNULL(('.$res.'), 0) as qtyLastMonth',
			'('.$res1.') as price',
			'm.name as name'));
		$this->db->from($this->table. " mi");
		$this->db->join('materials m', 'm.id = mi.materials_id');
		$this->db->join('material_categories mc', 'm.material_categories_id = mc.id');
		$this->db->join('receive_details rd', 'm.id = rd.materials_id and mi.receive_details_id = rd.id', 'left');
		$this->db->where("DATE_FORMAT(mi.date, '%Y-%m-%d') BETWEEN '".$this->input->post('start_date')."' AND '".$this->input->post('end_date')."' ", NULL, FALSE);
		$this->db->group_by('m.id, m.name');
 
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

	public function get_material_inventories($id)
	{
		$this->db->select(array('m.id as id', 'm.name as name', 'mi.date as date', 'mi.type as type', 'mi.qty as qty', 'mi.receive_details_id', 'mi.p_return_details_id', 'mi.material_usages_detail_id', 'adjustment', 'm.initial_qty', 'r.code as rcode', 'pr.code as prcode', 'mu.code_pick as mucodepick', 'mu.code_return as mucodereturn'),false);
		$this->db->from($this->table. " mi");
		$this->db->join('materials m', 'mi.materials_id = m.id', 'left');
		$this->db->join('receiving r', 'mi.receive_details_id = r.id', 'left');
		$this->db->join('purchase_return pr', 'mi.p_return_details_id = pr.id', 'left');
		$this->db->join('material_usages_detail mud', 'mi.material_usages_detail_id = mud.id', 'left');
		$this->db->join('material_usages mu', 'mud.material_usages_id = mu.id', 'left');
		$this->db->join('work_orders wo', 'mu.work_orders_id = wo.id', 'left');
		$this->db->where('mi.materials_id', $id);
		return $this->db->get()->result();
	}

	public function get_material_inventory($id)
	{
		$this->db->select(array('m.id as id', 'm.name as name', 'mi.date as date', 'mi.type as type', 'mi.qty as qty', 'mi.receive_details_id', 'mi.p_return_details_id', 'mi.material_usages_detail_id', 'm.initial_qty'),false);
		$this->db->from($this->table. " mi");
		$this->db->join('materials m', 'm.id = mi.materials_id');
		$this->db->where('mi.materials_id', $id);
		return $this->db->get()->row();
	}

	public function material_usage_change($id, $material_id, $detail, $type){
		$this->db->where('material_usages_detail_id', $id);
		$this->db->where('materials_id', $material_id);
		$this->db->where('type', $type);
		$row = $this->db->get($this->_t)->row();
		if(isset($row)){
			$qty = 0;
			if($type == "out"){
				$qty = $detail->qty_pick;
			}else{
				$qty = $detail->qty_return;
			}
			$data = array(
				'type' => $type,
				'qty' =>  $qty
			);
			$this->db->where('id', $row->id);
			$status = $this->db->update($this->_t, $data);
		}else{
			$qty = 0;
			if($type == "out"){
				$qty = $detail->qty_pick;
			}else{
				$qty = $detail->qty_return;
			}
			$data = array(
				'date' => date("Y-m-d h:m:s"),
				'type' => $type,
				'material_usages_detail_id' => $id,
				'qty' => $qty,
				'materials_id' => $detail->materials_id
			);
			$status = $this->db->insert($this->_t, $data);
		}
		return true;
	}

	public function material_usage_delete()
	{
		$this->db->where('material_usages_detail_id', $this->input->input_stream('id'));
		$this->db->where('materials_id', $this->input->input_stream('materials_id'));
		$this->db->delete($this->_t);
	}

	public function check_material_stock($id, $pick_qty)
	{
		$min_stock = 0;
		$initial = 0;
		$this->db->select('m.name as name, min_stock, symbol, initial_qty');	
		$this->db->join('uom u', 'm.uom_id = u.id', 'left');	
		$this->db->where('m.id', $id);	
		$material = $this->db->get('materials m')->row();
		if(isset($material)){
			$min_stock = $material->min_stock;
			$initial = $material->initial_qty;
		}

		$this->db->select("COALESCE(SUM(case type
			when 'in' then qty
			when 'out' then -qty
	    end), 0) as stock");
		$this->db->where('materials_id', $id);
		$stock = $this->db->get($this->_t)->row();
		if($stock->stock+$initial - $pick_qty >= 0){
			$data = array(
				'status' => true
			); 
			return $data;
		}
		return array(
			'status' => false,
			'msg' => $this->generate_material_stock_notif($material, $stock->stock+$initial, $min_stock, $pick_qty)
		);
	}

	public function generate_material_stock_notif($material, $stock, $min_stock,  $pick_qty)
	{
		return $material->name." is out of stock.<br>Pick amount : ".$pick_qty." ".$material->symbol.".<br>
		Available stock : ".$stock." ".$material->symbol."<br>Minimum stock : ".$min_stock." ".$material->symbol;
	}

}
