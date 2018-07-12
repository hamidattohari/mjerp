<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receive_det_model extends MY_Model {

	protected $_t = 'receive_details';
		
	var $table = 'receive_details';
	var $column = array('id','code', 'delivery_date'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('qty, unit_price, total_price, receiving_id, materials_id');
		$this->db->from($this->table);
 
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

	function get_receive_details($id){
        $this->db->select(array('receive_details.id as id', 'discount','materials.id as id_materials','qty','unit_price','total_price','materials_id'));
        $this->db->where('receiving_id', $id);
        $this->db->join('materials', 'materials.id = receive_details.materials_id', 'LEFT');
        $result = $this->db->get('receive_details');
        return $result->result();
	}

	function populate_receiving_details($id){
		$this->db->select('m.id as id, m.name as value');
		$this->db->where('receiving_id', $id);	
		$this->db->join('materials m', 'rd.materials_id = m.id', 'left');
		$result = $this->db->get($this->_t.' rd');
		return $result->result();
	}

	function get_receive_det_where_id($id){
        $this->db->select('rd.id, SUM(rd.qty) as qty, rd.materials_id, m.name as name, SUM(rd.unit_price) as unit_price, SUM(rd.total_price) as total_price');
		$this->db->from($this->table." rd");
		$this->db->join('materials m', 'rd.materials_id = m.id', 'left');
		$this->db->join('receiving r', 'rd.receiving_id = r.id', 'left');
        $this->db->where('r.purchasing_id', $id);
        $this->db->group_by('rd.materials_id, m.name');
        $result = $this->db->get();
        $data = array();
        if($result->result()){
            $data = $result->result();
        }
        return $data;
	}

	function get_receive_det_where_id1($id){
        $this->db->select('rd.id, rd.qty, rd.materials_id, m.name as name, rd.unit_price, rd.total_price, u.symbol as uom');
		$this->db->from($this->table." rd");
		$this->db->join('materials m', 'rd.materials_id = m.id', 'left');
		$this->db->join('uom u', 'm.uom_id = u.id', 'left');
		$this->db->join('receiving r', 'rd.receiving_id = r.id', 'left');
        $this->db->where('rd.receiving_id', $id);
        $result = $this->db->get();
        $data = array();
        if($result->result()){
            $data = $result->result();
        }
        return $data;
	}
}
