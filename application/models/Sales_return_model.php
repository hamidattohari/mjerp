<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_model extends MY_Model {

	protected $_t = 'sales_return';
		
	var $table = 'sales_return';
	var $column = array('sr.id', 'sr.code', 'date', 'ps.code'); //set column field database for order and search
    var $order = array('sr.id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('sr.id as id, sr.code as code, date, ps.code as p_code');
		$this->db->from($this->table.' sr');
		$this->db->join('product_shipping ps', 'sr.product_shipping_id = ps.id', 'left');
 
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
		$prefix = "SR-";
		$infix = date("Ymd-");
		$this->db->select("MAX(RIGHT(`code`, 4)) as 'maxID'");
        $this->db->like('code', $prefix.$infix, 'after');
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return $prefix.$infix.sprintf("%04s", $code);
	}

}
