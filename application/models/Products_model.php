<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends MY_Model {

	protected $_t = 'products';
		
	var $table = 'products';
	var $column = array('p.id', 'p.code', 'p.name', 'pc.name'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('p.id as id, p.code as code, p.name as name, pc.name as category, symbol');
		$this->db->from($this->table.' p');
		$this->db->where('p.deleted',0);
		$this->db->join('product_categories pc', 'p.product_categories_id = pc.id', 'left');
		$this->db->join('uom', 'p.uom_id = uom.id', 'left');
 
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

	public function generate_id(){
		$code = "fail";
		$suffix = "MC";
		$this->db->select("MAX(RIGHT(`code`, 5)) as 'maxID'");
		if($this->input->get('type') == "foil"){
			$this->db->like('code', $this->input->get('color'), 'after');
		}	
		$result = $this->db->get($this->_t);
		$code = substr($result->row(0)->maxID, 0, 3);
		$code++;
		$code = sprintf("%03s", $code);
		if($this->input->get('type') == "foil"){
			return $this->input->get('color').$code.$suffix;
		}else{
			return $code.$suffix;
		}
	}

	public function populate_autocomplete(){
		$this->db->like('code', $this->input->get('term'), 'both');
		$this->db->or_like('name', $this->input->get('term'), 'both');
		return $this->db->get($this->_t)->result();
	}

	public function populate_autocomplete_code(){
		if($this->input->get('category_id')){
			$this->db->where('product_categories_id', $this->input->get('category_id'));
		}
		$this->db->like('code', $this->input->get('term'), 'both');
		$this->db->order_by('code', 'desc');
		return $this->db->get($this->_t)->result();
	}

}
