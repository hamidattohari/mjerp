<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receive_model extends MY_Model {

	protected $_t = 'receiving';
		
	var $table = 'receiving';
	var $column = array('id','code', 'delivery_date'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('r.id as id, p.id as purchasing_id, p.code as code, p.delivery_date, r.id as id_receive, r.receive_date, IF(r.receive_date, "true", "false") as status');
		$this->db->from($this->table.' r');
		$this->db->join('purchasing p', 'r.purchasing_id = p.id', 'left');
 
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
		$prefix = "LPB/MC/";
		$month = $this->get_roman_number(date("n"))."/";
		$this->db->select("MAX(RIGHT(`code`, 3)) as 'maxID'");
        $this->db->like('code', $prefix.$month, 'after');
        $result = $this->db->get($this->_t);
        $code = $result->row(0)->maxID;
        $code++; 
        return $prefix.$month.sprintf("%03s", $code);
	}

	public function get_receiving($id)
	{
		$this->db->select('r.id as id, p.code as pcode, r.code as code, p.delivery_date, r.receive_date, p.vendors_id as vendors_id, v.name as name, r.no_sj as no_sj');
		$this->db->from($this->table." r");
		$this->db->join("purchasing p", "r.purchasing_id = p.id", "left");
		$this->db->join("vendors v", "p.vendors_id = v.id", "left");
		$this->db->where('r.id', $id);
		return $this->db->get()->row();
	}

}
