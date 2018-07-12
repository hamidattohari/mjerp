<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    
	protected $_t = '';

	var $table = '';
	var $column = array(); //set column field database for order and search
    var $order = array(); // default order
        
    function __construct() {
        parent::__construct();
        $this->load->database();
	}

	protected function _get_datatables_query() {
         
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

	function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }
    
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
        return $query->result();
	}

	function get_output_data(){
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->count_all(),
			"recordsFiltered" => $this->count_filtered(),
			"data" => $this->get_datatables(),
		);
		return $output;
	}

	public function get_all_data(){
		return $this->db->get($this->_t)->result();
	}

	public function count_all_data(){
		$this->db->from($this->_t);
		return $this->db->count_all_results();
	}

	function add($data){
        $inserted = $this->db->insert($this->_t, $data);     
        return $inserted;
	}

	function add_id($data){
        $this->db->insert($this->_t, $data);     
        $inserted = $this->db->insert_id();     
        return $inserted;
	}

	function add_batch($data){
		$this->db->insert_batch($this->_t, $data);
	}
	
	function get_by_id($column, $id){
        $this->db->where($column, $id);
        $result = $this->db->get($this->_t);
        $data = array();
        if($result->result()){
            $data = $result->row();
        }
        return $data;
	}

	function get_where_id($column, $id){
        $this->db->where($column, $id);
        $result = $this->db->get($this->_t);
        $data = array();
        if($result->result()){
            $data = $result->result();
        }
        return $data;
	}
	
	function update($column, $id, $data){
        $this->db->where($column, $id);
        $this->db->update($this->_t, $data);
        $num_removed = $this->db->affected_rows();
        if($num_removed == 1){
            return TRUE;
        }else{
            return TRUE;
        }
    }

    function update_id($column, $id, $data){
        $this->db->where($column, $id);
        $this->db->update($this->_t, $data);
        $num_removed = $this->db->affected_rows();
        if($num_removed == 1){
            return $id;
        }else{
            return $id;
        }
    }

	function delete($column, $id){
        $this->db->delete($this->_t, array($column => $id));
        $num_removed = $this->db->affected_rows();
        if($num_removed == 1){
            return TRUE;
        }
        return FALSE;
	}
	
	function delete2($col, $id){
        $this->db->set('deleted', 1);
		$this->db->where($col, $id);
		$this->db->update($this->_t);
        $num_removed = $this->db->affected_rows();
        if($num_removed == 1){
            return $id;
        }else{
            return $id;
        }
    }

    function get_by_id_by_table($column, $id, $table){
        $this->db->where($column, $id);
        $result = $this->db->get($table);
        return $result->result();
	}
	
	function get_roman_number($number){
		$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
		$returnValue = '';
		while ($number > 0) {
			foreach ($map as $roman => $int) {
				if($number >= $int) {
					$number -= $int;
					$returnValue .= $roman;
					break;
				}
			}
		}
		return $returnValue;
	}

	function update2($column, $id, $data){
        $this->db->where($column, $id);
        $this->db->update($this->_t, $data);
        $num_affected = $this->db->affected_rows();
        return $num_affected > 0;
	}
	
	function convert_currency($source_curr, $amount)
	{
		$this->db->select('currency_id');		
		$row = $this->db->get('company_info')->row();
		$target_curr = $row->currency_id;

		if($target_curr == null || $target_curr == $source_curr){
			return $amount;
		}else{
			$this->db->select('rate');		
			$this->db->where('id', $source_curr);		
			$row = $this->db->get('currency')->row();
			$rate = $row->rate; 
			if($source_curr == 1){
				return $amount / $rate;
			}else{
				return $amount * $rate;
			}
		}
	}

}
