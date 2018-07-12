<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_report_model extends MY_Model {

	protected $_t = 'product_shipping';
		
	var $table = 'product_shipping';
	var $column = array('ps.id', 'ps.shipping_date', 'ps.code', 'ps.transport', 'ps.note', 'prcode'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('ps.id as id, ps.shipping_date as date, ps.code as code, ps.transport, ps.note, p.code as prcode');
		$this->db->from($this->table.' ps');
		$this->db->where("DATE_FORMAT(ps.created_at, '%Y-%m-%d') BETWEEN '".$this->input->post('start_date')."' AND '".$this->input->post('end_date')."' ", NULL, FALSE);
		$this->db->join('projects p', 'ps.projects_id = p.id', 'left');
 
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
