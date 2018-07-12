<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_movement_det_model extends MY_Model {

	protected $_t = 'product_movement_details';
		
	var $table = 'product_movement_details';
	var $column = array('id','code','date','product_movement_id','processes_id'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('pmd.ad as id, p.name as name, pmd.date, pmd.code');
		$this->db->join("product_movement pm", "pm.id = pmd.product_movement_id", "left");
		$this->db->join("products p", "p.id = pm.products_id", "left");
		$this->db->from($this->table. " pmd");
 
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

	public function get_last_no($code){
		$this->db->select('IFNULL(no,0) as no');
		$this->db->where('code', $code);
		return $this->db->order_by('id',"desc")->limit(1)->get($this->table)->row();
	}

	public function get_product_movement_detail($woid, $pid, $prid)
	{
		$this->db->select('IFNULL(sum(pmd1.qty),0)');
		$this->db->from($this->table." pmd1");
		$this->db->where('pmd1.from_id = pmd.id');
		$res = $this->db->get_compiled_select();

		$this->db->select(array('pmd.id as id', 'pmd.code as code', 'pc.id as processes_id', 'pmd.note as note', 'pmd.date as date', 'pmd.qty - ('.$res.') as qty', 'pmd.no', 'pm.created_by', 'pm.id as pm_id', 'pmd.length as length', 'pmd.note as note'));
		$this->db->from($this->table." pmd");
		$this->db->join('processes pc', 'pmd.processes_id = pc.id', 'left');
		$this->db->join('product_movement pm', 'pmd.product_movement_id = pm.id', 'left');
		$this->db->join('products p', 'pm.products_id = p.id', 'left');
		$this->db->where('pm.work_orders_id', $woid);
		$this->db->where('pm.products_id', $pid);
		$this->db->where('pmd.processes_id', $prid);
		return $this->db->get()->result();
	}

	public function get_product_movement_detail1($woid, $pid, $prid)
	{
		$this->db->select('IFNULL(sum(pmd1.length),0)');
		$this->db->from($this->table." pmd1");
		$this->db->where('pmd1.from_id = pmd.id');
		$this->db->where('pmd1.processes_id', $prid+1);
		$res = $this->db->get_compiled_select();

		$this->db->select(array('pmd.id as id', 'pmd.code as code', 'pc.id as processes_id', 'pmd.note as note', 'pmd.date as date', 'pmd.qty as qty', 'pmd.no', 'pm.created_by', 'pm.id as pm_id', 'pmd.length - ('.$res.') as length', 'pmd.length * 4 as length1'), false);
		$this->db->from($this->table." pmd");
		$this->db->join('processes pc', 'pmd.processes_id = pc.id', 'left');
		$this->db->join('product_movement pm', 'pmd.product_movement_id = pm.id', 'left');
		$this->db->join('products p', 'pm.products_id = p.id', 'left');
		$this->db->where('pm.work_orders_id', $woid);
		$this->db->where('pm.products_id', $pid);
		$this->db->where('pmd.processes_id', $prid);
		return $this->db->get()->result();
	}

	public function get_product_movement_detail_print($date, $prid)
	{
		$this->db->select(array('pmd.id as id', 'p.id', 'p.name as name', 'COUNT(*) as qty','u.symbol as uom', 'pmd.date as date', 'wo.code as wo'));
		$this->db->from($this->table." pmd");
		$this->db->join('product_movement pm', 'pmd.product_movement_id = pm.id', 'left');
		$this->db->join('work_orders wo', 'pm.work_orders_id = wo.id', 'left');
		$this->db->join('products p', 'pm.products_id = p.id', 'left');
		$this->db->join('uom u', 'p.uom_id = u.id', 'left');
		$this->db->like('pmd.date', $date);
		$this->db->where('pmd.processes_id', $prid);
		$this->db->group_by('p.id');
		return $this->db->get()->result();
	}
}
