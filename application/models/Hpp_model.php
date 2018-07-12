<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hpp_model extends MY_Model {

	protected $_t = 'hpp';
		
	var $table = 'hpp';
	var $column = array('h.id','h.code', 'p.code', 'p.name'); //set column field database for order and search
    var $order = array('h.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('h.id as id, h.code as code, wo.code as wocode, h.products_id, p.name as name, h.month, h.year');
		$this->db->from($this->_t.' h');
		$this->db->join('work_orders wo', 'h.work_orders_id = wo.id', 'left');
		$this->db->join('products p', 'h.products_id = p.id', 'left');
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

	public function get_hpp_by_id($id)
	{
		$this->db->select('h.id as id, h.code as code, wo.code as wocode,h.penyusutan,h.listrik,h.lain_lain,wo.id as work_orders_id, p.id as products_id, h.products_id, p.name as pname, month(h.created_at) as month, year(h.created_at) as year');
		$this->db->from($this->_t.' h');
		$this->db->join('work_orders wo', 'h.work_orders_id = wo.id', 'left');
		$this->db->join('products p', 'h.products_id = p.id', 'left');
		$this->db->where('h.id', $id);
		return $this->db->get()->row();
	}

	public function generate_id(){
		$seg2 = "/HPP-MJ";
		$seg3 = "/".$this->get_roman_number($this->input->post('month'));
		$seg4 = "/".substr($this->input->post('year'), 2, 2);
		$this->db->select("MAX(LEFT(`code`, 2)) as 'maxID'");
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return sprintf("%02s", $code).$seg2.$seg3.$seg4;
	}

	public function get_total_btkl($month, $year)
	{
		$this->db->select('SUM(qty*total) as `total`', false);
		$this->db->where('month', $month);
		$this->db->where('year', $year);
		$this->db->group_by('month, year');
		$row = $this->db->get('btkl')->row();
		if(isset($row)){
			return $row->total;
		}
		return 0;
	}
	public function get_total_bop($id)
	{
		$this->db->select('penyusutan + listrik + lain_lain as `total`', false);
		$this->db->where('id', $id);
		$row = $this->db->get($this->_t)->row();
		if(isset($row)){
			return $row->total;
		}
		return 0;
	}

	public function get_material_list($id, $wo_id)
	{
		$this->db->where('id', $id);
		$hpp = $this->db->get($this->_t)->row();

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

	public function get_per_pieces_price($id)
	{
		$this->db->select('SUM(total_price-discount)/SUM(qty) as `price`');
		$this->db->where('materials_id', $id);
		$row = $this->db->get('receive_details')->row();
		if(isset($row)){
			return $row->price;
		}
		return 0;
	}

	public function get_all_wos($pid)
	{

		$this->db->select('DISTINCT(wo.id) as id', FALSE);
		$this->db->join('work_orders wo', 'wod.work_orders_id = wo.id', 'left');
		$this->db->where('wod.products_id', $pid);
		$result = $this->db->get('work_order_detail wod')->result();
		return $result;
	}

	public function get_product_result($wos, $pid)
	{
		$data = array();
		$data['initital'] = $this->get_initial_amount($wos, $pid);
		$data['intermediate'] = $this->get_intermediate_amount($wos, $pid);
		$data['final'] = $this->get_final_amount($wos, $pid);
		return $data;
	}

	public function get_initial_amount($wos, $pid)
	{
		$total = 0;
		foreach($wos as $wo){
			$this->db->select('COALESCE(SUM(qty),0) as qty', false);			
			$this->db->where('work_orders_id', $wo->id);
			$this->db->where('products_id', $pid);
			$this->db->group_by('products_id');
			$row = $this->db->get('work_order_detail')->row();
			if(isset($row)){
				$total += $row->qty;
			}
		}	
		return $total;
	}

	public function get_intermediate_amount($wos, $pid)
	{
		$total = 0;
		foreach($wos as $wo){
			$this->db->select('COALESCE(SUM(pmd.qty)) as qty', false);			
			$this->db->join('product_movement pm', 'pmd.product_movement_id = pm.id', 'left');
			$this->db->where('pm.work_orders_id', $wo->id);
			$this->db->where('pm.products_id', $pid);
			$this->db->where('pmd.processes_id', -1);
			$row = $this->db->get('product_movement_details pmd')->row();
			if(isset($row)){
				$total += $row->qty;
			}
		}	
		return $total;
	}

	public function get_final_amount($wos, $pid)
	{
		$total = 0;
		foreach($wos as $wo){
			$this->db->select('COALESCE(SUM(pmd.qty)) as qty', false);			
			$this->db->join('product_movement pm', 'pmd.product_movement_id = pm.id', 'left');
			$this->db->where('pm.work_orders_id', $wo->id);
			$this->db->where('pm.products_id', $pid);
			$this->db->where('pmd.processes_id', 0);
			$row = $this->db->get('product_movement_details pmd')->row();
			if(isset($row)){
				$total += $row->qty;
			}
		}	
		return $total;
	}

}
