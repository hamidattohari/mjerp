<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unfinished_product_inventory_model extends MY_Model {

	protected $_t = 'unfinished_product_inventory';
		
	var $table = 'unfinished_product_inventory';
	var $column = array('p.id', 'debit', 'credit','qty', 'name'); //set column field database for order and search
    var $order = array('p.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {

		$this->db->select('SUM(CASE WHEN upi1.type = "in" THEN upi1.qty ELSE 0 END)+p.initial_half_qty-SUM(CASE WHEN upi1.type = "out" THEN upi1.qty ELSE 0 END)');
		$this->db->from($this->table. " upi1");
		$this->db->where('YEAR(upi1.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(upi1.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
		$this->db->where('upi1.id = upi.id');
		$res = $this->db->get_compiled_select();
         
		$this->db->select(array('p.id as id', 
			'SUM(CASE WHEN upi.type = "in" THEN upi.qty ELSE 0 END)+p.initial_half_qty as debit', 
			'SUM(CASE WHEN upi.type = "out" THEN upi.qty ELSE 0 END) as credit',
			'SUM(CASE WHEN upi.type = "in" THEN upi.qty ELSE 0 END)+p.initial_half_qty-SUM(CASE WHEN upi.type = "out" THEN upi.qty ELSE 0 END) as qty', 'p.name as name',
			'IFNULL(('.$res.'), 0) as qtyLastMonth'));
		$this->db->from($this->table. " upi");
		$this->db->join('products p', 'p.id = upi.products_id');
		$this->db->where("DATE_FORMAT(upi.date, '%Y-%m-%d') BETWEEN '".$this->input->post('start_date')."' AND '".$this->input->post('end_date')."' ", NULL, FALSE);
		$this->db->group_by('p.id, p.name');
 
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

	public function get_unfinished_product_inventories($id)
	{
		$this->db->select(array('p.id as id', 'p.name as name', 'upi.date as date', 'upi.type as type', 'upi.qty as qty', 'upi.nonmaterial_usages_detail_id', 'upi.product_movement_id', 'p.initial_half_qty', 'wo.code as wocode', 'mu.code_pick as mucodepick', 'mu.code_return as mucodereturn'),false);
		$this->db->from($this->table. " upi");
		$this->db->join('products p', 'upi.products_id = p.id', 'left');
		$this->db->join('product_movement pm', 'upi.product_movement_id = pm.id', 'left');
		$this->db->join('work_orders wo', 'pm.work_orders_id = wo.id', 'left');
		$this->db->join('nonmaterial_usages_detail mud', 'upi.nonmaterial_usages_detail_id = mud.id', 'left');
		$this->db->join('material_usages mu', 'mud.material_usages_id = mu.id', 'left');
		$this->db->where('upi.products_id', $id);
		return $this->db->get()->result();
	}

	public function get_unfinished_product_inventory($id)
	{
		$this->db->select(array('p.id as id', 'p.name as name', 'upi.date as date', 'upi.type as type', 'upi.qty as qty', 'upi.nonmaterial_usages_detail_id', 'upi.product_movement_id', 'p.initial_half_qty'),false);
		$this->db->from($this->table. " upi");
		$this->db->join('products p', 'upi.products_id = p.id', 'left');
		$this->db->where('upi.products_id', $id);
		return $this->db->get()->row();
	}

	public function material_usage_change($id, $products_id, $detail, $type){
		$this->db->where('nonmaterial_usages_detail_id', $id);
		$this->db->where('products_id', $products_id);
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
				'date' => date("Y-m-d h:m:s"),
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
				'nonmaterial_usages_detail_id' => $id,
				'qty' => $qty,
				'products_id' => $detail->products_id
			);
			$status = $this->db->insert($this->_t, $data);
		}
		return true;
	}

	public function material_usage_delete()
	{
		$this->db->where('nonmaterial_usages_detail_id', $this->input->input_stream('id'));
		$this->db->where('products_id', $this->input->input_stream('products_id'));
		$this->db->delete($this->_t);
	}

	public function check_material_stock($id, $pick_qty)
	{
		$min_stock = 0;
		$initial = 0;
		$this->db->select('p.name as name, symbol, initial_half_qty');	
		$this->db->join('uom u', 'p.uom_id = u.id', 'left');	
		$this->db->where('p.id', $id);	
		$product = $this->db->get('products p')->row();
		if(isset($product)){
			$initial = $product->initial_half_qty;
		}

		$this->db->select("COALESCE(SUM(case type
			when 'in' then qty
			when 'out' then -qty
	    end), 0) as stock");
		$this->db->where('products_id', $id);
		$stock = $this->db->get($this->_t)->row();
		if($stock->stock+$initial - $pick_qty >= 0){
			$data = array(
				'status' => true
			); 
			return $data;
		}
		return array(
			'status' => false,
			'msg' => $this->generate_material_stock_notif($product, $stock->stock+$initial, 0, $pick_qty)
		);
	}

	public function generate_material_stock_notif($product, $stock, $min_stock,  $pick_qty)
	{
		return $product->name." is out of stock.<br>Pick amount : ".$pick_qty." ".$product->symbol.".<br>
		Available stock : ".$stock." ".$product->symbol."<br>Minimum stock : ".$min_stock." ".$product->symbol;
	}

}
