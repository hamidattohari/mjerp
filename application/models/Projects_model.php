<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends MY_Model {

	protected $_t = 'projects';
		
	var $table = 'projects';
	var $column = array('p.id', 'p.created_at', 'p.code', 'p.vat', 'p.description', 'c.name'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('p.id as id, p.created_at as date, p.code as code, p.vat as vat, p.description, c.name as customer, p.po_cust');
		$this->db->from($this->table.' p');
		$this->db->join('customers c', 'p.customers_id = c.id', 'left');
 
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
		$vat = "P";
		if($this->input->get('vat') == 0){
			$vat = "NP";
		}
		$seg1 = "/MC-".$vat; 
		$seg2 = "/SO-".$this->get_roman_number(date("n")); 
		$seg3 = "/".date("Y"); 
		$this->db->select("MAX(LEFT(`code`, 3)) as 'maxID'");
        $this->db->like('code', $seg1.$seg2.$seg3, 'before');
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return sprintf("%03s", $code).$seg1.$seg2.$seg3;
	}

	public function populate_autocomplete(){
		$this->db->like('code', $this->input->get('term'), 'both');
		return $this->db->get($this->_t)->result();
	}

	public function get_details($id)
	{
		$this->db->select('p.id as id, p.code as code, vat, c.id as customers_id, c.name customer_name, p.description, p.po_cust');
		$this->db->join('customers c', 'p.customers_id = c.id', 'left' );
		$this->db->where('p.id', $id);
		$result = $this->db->get($this->_t.' p' )->row();
		return $result;
	}

}
