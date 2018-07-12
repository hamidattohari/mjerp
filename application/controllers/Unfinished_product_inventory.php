<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unfinished_product_inventory extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('unfinished_product_inventory_model', 'upi');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('no', '', 'No');
        $table->addColumn('name', '', 'Name');       
        $table->addColumn('qtyLastMonth', '', 'Balance LastMonth'); 
        $table->addColumn('debit', '', 'Debit');       
        $table->addColumn('credit', '', 'Credit');       
        $table->addColumn('qty', '', 'Balance');        
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }

    private function get_column_attr1(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('date', '', 'Date');
        $table->addColumn('type', '', 'Type');      
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }

    public function get_product_categories(){
		$result = $this->upi->get_all_data();
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['Name'] = $value->name;
			$row['Id'] = $value->id;
			$data[] = $row;
			$count++;
		}

		$result = $data;
		echo json_encode($result);
	}
	
	public function index()
	{
		$data['title'] = "ERP | Unfinished Product Inventory";
		$data['page_title'] = "Unfinished Product Inventory";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Inventory", "Unfinished Product Inventory");
		$data['page_view']  = "inventory/unfinished_product_inventory";		
		$data['js_asset']   = "unfinished-product-inventory";	
		$data['columns']    = $this->get_column_attr();
		$data['columns1']    = $this->get_column_attr1();
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();		
		$this->add_history($data['page_title']);	
		$this->add_history($data['page_title']);		
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->upi->get_output_data();
        $data = array();
        $count = 0;
        foreach($result['data'] as $value){
            $count++;
            $row = array();
            $row['id'] = $value->id;
			$row['name'] = $value->name;
			$row['qtyLastMonth'] = number_format($value->qtyLastMonth);
			$row['no'] = $count;
			$row['debit'] = $value->debit;
			$row['credit'] = $value->credit;
			$row['qty'] = $value->qty;
			$row['actions'] = '<button class="btn btn-sm btn-info" onclick="edit('.$value->id.')" type="button">View Detail</button>';
            $data[] = $row;
        }

        $result['data'] = $data;

        echo json_encode($result);
	}

	public function get_unfinished_product_inventory($id){
		$result = $this->upi->get_unfinished_product_inventory($id);
        echo json_encode($result);
	}

	function add(){
		$data = array(
			'products_id' => $this->normalize_text($this->input->post('products_id')),
			'type' => $this->normalize_text($this->input->post('type')),
			'date' => date("Y-m-d H:i:s"),
			'qty' => $this->normalize_text($this->input->post('qty')),
			'adjustment' => 1
		);
		$inserted = $this->upi->add($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->upi->get_by_id('products_id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('name'))
		);
		$status = $this->upi->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->upi->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id = -1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->upi->get_unfinished_product_inventories($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['name'] = $value->name;
				$row['date'] = $value->date;
				$row['type'] = $value->type;
				$row['qty'] = $value->qty;
				if ($value->nonmaterial_usages_detail_id != 0) {
					if ($value->type == "out") {
						$row['status'] = "pickup";
						$row['code'] = $value->mucodepick;
					} elseif ($value->type == "in") {
						$row['status'] = "return";
						$row['code'] = $value->mucodereturn;
					}
				} elseif ($value->product_movement_id) {
					$row['status'] = "production";
					$row['code'] = $value->wocode;
				} elseif ($value->adjustment) {
					$row['status'] = "adjustment";
					$row['status'] = "-";
				}
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;
		}
	}

}
