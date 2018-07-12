<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_report_model extends MY_Model {

	protected $_t = 'purchasing';
		
	var $table = 'purchasing';
	var $column = array('p.id', 'p.created_at', 'p.code', 'p.vat', 'c.name'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('p.id as id, p.created_at as date, p.code as code, p.vat as vat, p.note, v.name as vendor, sum(pd.total_price-pd.discount) as price, GROUP_CONCAT(m.name SEPARATOR ", ") as name');
		$this->db->from($this->table.' p');
		$this->db->join('purchase_details pd', 'p.id = pd.purchasing_id', 'left');
		$this->db->join('materials m', 'pd.materials_id = m.id', 'left');
		$this->db->join('vendors v', 'p.vendors_id = v.id', 'left');
		$this->db->where("DATE_FORMAT(p.created_at, '%Y-%m-%d') BETWEEN '".$this->input->post('start_date')."' AND '".$this->input->post('end_date')."' ", NULL, FALSE);
		$this->db->group_by('p.id');
 
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
