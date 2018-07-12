<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_movement extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('product_movement_model', 'pmm');
		$this->load->model('product_movement_det_model', 'pmdm');
		$this->load->model('processes_model', 'prcm');
		$this->load->model('work_orders_model', 'wom');
		$this->load->model('work_order_detail_model', 'wodm');
	}

	private function get_column_attr(){
		$table = new TableField();
		$table->addColumn('no', '', 'No');
		$table->addColumn('id', '', 'ID');
		$table->addColumn('code', '', 'Code');         
		$table->addColumn('projects_code', '', 'Sales Order');        
		$table->addColumn('ppn', '', 'VAT');        
		$table->addColumn('actions', '', 'Actions');        
		return $table->getColumns();
	}
	
	private function get_column_attr1(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('qty', '', 'Qty');    
		$table->addColumn('name', '', 'Products');     
		$table->addColumn('actions', '', 'Actions');  
		return $table->getColumns();
	}
	
	public function index()
	{
		$data['title'] = "ERP | Product Movement";
		$data['page_title'] = "Product Movement";
		$data['table_title'] = "List Work Orders";		
		$data['table_title1'] = "List Products";		
		$data['breadcumb']  = array("Production", "Product Movement");
		$data['page_view']  = "production/product_movement";		
		$data['js_asset']   = "product-movement";	
		$data['columns']    = $this->get_column_attr();	
		$data['columns1']    = $this->get_column_attr1();	
		$data['process'] = $this->prcm->get_all_data();	
		$data['menu'] = $this->get_menu();					
		$data['csrf'] = $this->csrf;		
		$this->add_history($data['page_title']);
		$this->load->view('layouts/master', $data);
	}

	public function view_data($id){
		$result = $this->wodm->get_work_order_details($id);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['qty'] = $value->qty;
			$row['name'] = $value->name;
			$row['actions'] = '<a href=product_movement_detail/details/'.$value->woid.'/'.$value->pid.'><button class="btn btn-sm btn-info" type="button">Details</button></a>';
			$data[] = $row;
			$count++;
		}

		$result['data'] = $data;

		echo json_encode($result);
	}

	function add(){
		$data = array(
			'receive_date' => $this->input->post('receive_date'),
			'processes_id' => $this->input->post('processes_id'),
			'processes_id1' => $this->input->post('processes_id1')
		);
		$inserted = $this->prm->add_id($data);
		echo json_encode(array('id' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->prm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'receive_date' => $this->input->post('receive_date'),
			'processes_id' => $this->input->post('processes_id'),
			'processes_id1' => $this->input->post('processes_id1')
		);
		$status = $this->prm->update_id('id', $this->input->post('change_id'), $data);
		echo json_encode(array('id' => $status));
	}

	function delete($id){        
		$status = $this->prm->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($date = -1, $prid = -1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->pmdm->get_product_movement_detail_print($date, $prid);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['product'] = $value->name;
				$row['qty'] = $value->qty;
				$row['uom'] = $value->uom;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;
		}
	}


}
