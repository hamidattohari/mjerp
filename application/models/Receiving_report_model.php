<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving_report_model extends MY_Model {

	protected $_t = 'receiving';
		
	var $table = 'receiving';
	var $column = array('r.id', 'r.receive_date', 'r.code', 'r.note', 'pcode'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('r.id as id, r.receive_date as date, r.code as code, r.note, p.code as pcode, v.name as vendor, GROUP_CONCAT(m.name SEPARATOR ", ") as name, sum(rd.qty) as qty');
		$this->db->from($this->table.' r');
		$this->db->join('purchasing p', 'r.purchasing_id = p.id', 'left');
		$this->db->join('receive_details rd', 'r.id = rd.receiving_id', 'left');
		$this->db->join('materials m', 'rd.materials_id = m.id', 'left');
		$this->db->join('vendors v', 'p.vendors_id = v.id', 'left');
		$this->db->where("DATE_FORMAT(r.created_at, '%Y-%m-%d') BETWEEN '".$this->input->post('start_date')."' AND '".$this->input->post('end_date')."' ", NULL, FALSE);
		$this->db->group_by('r.id');
 
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
