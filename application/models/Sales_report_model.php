<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_report_model extends MY_Model {

	protected $_t = 'projects';
		
	var $table = 'projects';
	var $column = array('p.id', 'p.created_at', 'p.code', 'p.vat', 'p.description', 'c.name'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('p.id as id, p.created_at as date, p.code as code, p.vat as vat, p.description, c.name as customer');
		$this->db->from($this->table.' p');
		$this->db->where("DATE_FORMAT(p.created_at, '%Y-%m-%d') BETWEEN '".$this->input->post('start_date')."' AND '".$this->input->post('end_date')."' ", NULL, FALSE);
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

}
