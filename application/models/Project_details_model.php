<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_details_model extends MY_Model {

	protected $_t = 'project_details';

	var $table = 'project_details';
	var $column = array('pd.id', 'pr.name', 'pd.qty','u.symbol','price', 'total_price', 'pd.note'); //set column field database for order and search
    var $order = array('id' => 'asc'); // default order 
	
	protected function _get_datatables_query() {
         
		$this->db->select('pd.id as id, pr.name as name, pd.qty as qty, u.symbol as symbol, price, total_price, pd.note');
		$this->db->from($this->table.' pd');
		$this->db->join('projects p', 'pd.projects_id = p.id', 'left');
		$this->db->join('products pr', 'pd.products_id = pr.id', 'left');
		$this->db->join('uom u', 'pr.uom_id = u.id', 'left');
		$this->db->where('pd.projects_id', $this->input->post('projects_id'));
 
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

	function get_project_details($id){
		$this->db->select('pd.id as id, pd.qty as qty, products_id, p.name, u.symbol, price, total_price, note');
		$this->db->where('projects_id', $id);
		$this->db->join('products p', 'pd.products_id = p.id', 'left');
		$this->db->join('uom u', 'p.uom_id = u.id', 'left');
		$result = $this->db->get($this->_t.' pd');
		return $result->result();
	}

	function get_project_details_by_id($id){
		$this->db->select('pd.id as id, pd.qty as qty, products_id, p.name as name, p.code as code, price, total_price, note');
		$this->db->where('pd.id', $id);
		$this->db->join('products p', 'pd.products_id = p.id', 'left');
		$result = $this->db->get($this->_t.' pd')->row();
		return $result;
	}

	function populate_product_select($id){
		$this->db->select('p.id as id, p.name as value');
		$this->db->where('projects_id', $id);	
		$this->db->join('products p', 'pd.products_id = p.id', 'left');
		$result = $this->db->get($this->_t.' pd');
		return $result->result();
	}

	function populate_project_details($id){
		$this->db->select('p.id as id, p.name as name');
		$this->db->where('projects_id', $id);	
		$this->db->join('products p', 'pd.products_id = p.id', 'left');
		$result = $this->db->get($this->_t.' pd');
		return $result->result();
	}

	function check_project_details($id){
		$this->db->select('pd.id as id, pd.qty as qty, products_id, p.name, u.symbol, price, total_price, note');
		$this->db->where('projects_id', $id);
		$this->db->join('products p', 'pd.products_id = p.id', 'left');
		$this->db->join('uom u', 'p.uom_id = u.id', 'left');
		$result = $this->db->get($this->_t.' pd');
		return $result->row();
	}

}
