<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_det_model extends MY_Model {

	protected $_t = 'purchase_details';
		
	var $table = 'purchase_details';
	var $column = array('id','qty', 'unit_price', 'total_price', 'materials_id'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('pd.id, pd.qty, pd.unit_price, pd.total_price, pd.materials_id, m.name as name');
		$this->db->from($this->table." pd");
		$this->db->join('materials m', 'm.id = pd.materials_id');
 
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

	function get_purchase_details($id){
		$this->db->select('sum(rd.qty)');
		$this->db->from('receiving r');
		$this->db->join('receive_details rd', 'rd.receiving_id = r.id', 'left');
		$this->db->where('rd.materials_id = pd.materials_id');
		$this->db->where('r.purchasing_id = pc.id');
		$res = $this->db->get_compiled_select();

        $this->db->select('pd.id as id, m.id as materials_id, m.name as name, qty, price, discount, total_price, pd.note as note, u.symbol as uom, ('.$res.') as rqty');
        $this->db->where('purchasing_id', $id);
        $this->db->join('materials m', 'm.id = pd.materials_id', 'LEFT');
        $this->db->join('uom u', 'u.id = m.uom_id', 'LEFT');
        $this->db->join('purchasing pc', 'pd.purchasing_id = pc.id', 'LEFT');
        $result = $this->db->get('purchase_details pd');
        return $result->result();
	}

	public function check_received($id)
	{
		$this->db->select('purchasing_id, materials_id');
		$this->db->from($this->table." pd");
		$this->db->where('id', $id);
		$row = $this->db->get()->row();

		$this->db->from('receive_details rd');
		$this->db->join('receiving r', 'rd.receiving_id = r.id', 'left');
		$this->db->where('r.purchasing_id', $row->purchasing_id);
		$this->db->where('rd.materials_id', $row->materials_id);
		if ($this->db->count_all_results() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}
