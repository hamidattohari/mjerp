<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_inventory_model extends MY_Model {

	protected $_t = 'product_inventory';
		
	var $table = 'product_inventory';
	var $column = array('p.id', 'debit', 'credit','qty', 'name'); //set column field database for order and search
    var $order = array('p.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {

		$this->db->select('SUM(CASE WHEN pi1.type = "in" THEN pi1.qty ELSE 0 END)+p.initial_qty-SUM(CASE WHEN pi1.type = "out" THEN pi1.qty ELSE 0 END)');
		$this->db->from($this->table. " pi1");
		$this->db->where('YEAR(pi1.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(pi1.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
		$this->db->where('pi1.id = pi.id');
		$res = $this->db->get_compiled_select();
         
		$this->db->select(array('p.id as id', 
			'SUM(CASE WHEN pi.type = "in" THEN pi.qty ELSE 0 END)+p.initial_qty as debit', 
			'SUM(CASE WHEN pi.type = "out" THEN pi.qty ELSE 0 END) as credit',
			'SUM(CASE WHEN pi.type = "in" THEN pi.qty ELSE 0 END)+p.initial_qty-SUM(CASE WHEN pi.type = "out" THEN pi.qty ELSE 0 END) as qty', 'p.name as name',
			'IFNULL(('.$res.'), 0) as qtyLastMonth'));
		$this->db->from($this->table. " pi");
		$this->db->join('products p', 'p.id = pi.products_id');
		$this->db->where("DATE_FORMAT(pi.date, '%Y-%m-%d') BETWEEN '".$this->input->post('start_date')."' AND '".$this->input->post('end_date')."' ", NULL, FALSE);
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

	public function get_product_inventories($id)
	{
		$this->db->select(array('p.id as id', 'p.name as name', 'pi.date as date', 'pi.type as type', 'pi.qty as qty', 'pi.product_shipping_detail_id', 'pi.s_return_details_id', 'pi.product_movement_id', 'adjustment', 'p.initial_qty', 'wo.code as wocode', 'ps.code as scode', 'sr.code as srcode'),false);
		$this->db->from($this->table. " pi");
		$this->db->join('products p', 'pi.products_id = p.id', 'left');
		$this->db->join('product_movement pm', 'pi.product_movement_id = pm.id', 'left');
		$this->db->join('work_orders wo', 'pm.work_orders_id = wo.id', 'left');
		$this->db->join('product_shipping ps', 'pi.product_shipping_detail_id = ps.id', 'left');
		$this->db->join('sales_return sr', 'pi.s_return_details_id = sr.id', 'left');
		$this->db->where('pi.products_id', $id);
		return $this->db->get()->result();
	}

	public function get_product_inventory($id)
	{
		$this->db->select(array('p.id as id', 'p.name as name', 'pi.date as date', 'pi.type as type', 'pi.qty as qty', 'pi.product_shipping_detail_id', 'pi.s_return_details_id', 'pi.product_movement_id', 'p.initial_qty'),false);
		$this->db->from($this->table. " pi");
		$this->db->join('products p', 'pi.products_id = p.id', 'left');
		$this->db->where('pi.products_id', $id);
		return $this->db->get()->row();
	}


}
