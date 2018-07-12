<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Work_orders extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('projects_model', 'pm');
		$this->load->model('work_orders_model', 'wom');		
		$this->load->model('work_order_detail_model', 'wodm');		
		$this->load->model('project_details_model', 'pdm');
	}
	
	private function get_column_attr(){
		$table = new TableField();
		$table->addColumn('no', '', 'No');
		$table->addColumn('id', '', 'ID');
		$table->addColumn('code', '', 'Code');     
		$table->addColumn('start_date', '', 'Start Date');
		$table->addColumn('end_date', '', 'End date');
		$table->addColumn('projects_code', '', 'Sales Order');        
		$table->addColumn('ppn', '', 'VAT');        
		$table->addColumn('actions', '', 'Actions');        
		return $table->getColumns();
	}
	
	public function index()
	{
		$data['title'] = "ERP | Work Orders";
		$data['page_title'] = "Work Orders";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Production", "Work Orders");
		$data['page_view']  = "production/work_orders";		
		$data['js_asset']   = "work-orders";	
		$data['columns']    = $this->get_column_attr();		
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	public function generate_id(){
		$id = $this->wom->generate_id();
		echo json_encode(array('id' => $id));
	}

	public function populate_wo_select(){
		$result = $this->wom->populate_wo_select();
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['Name'] = $value->wo_code;
			$row['Id'] = $value->id;
			$data[] = $row;
			$count++;
		}

		$result = $data;
		echo json_encode($result);
	}

	public function populate_product_select($id=-1){
		$result = $this->wodm->populate_product_select($id);
		echo json_encode($result);
	}

	public function populate_autocomplete(){
		$result = $this->wom->populate_autocomplete();
		$data = array();
		foreach($result as $value){
			$row = array();
			$row['value'] = $value->code;
			$row['id'] = $value->id;
			$data[] = $row;
		}

		$result = $data;
		echo json_encode($result);
	}


	public function view_data(){
		$result = $this->wom->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$count++;
			$row = array();
			$row['no'] = $count;
			$row['id'] = $value->id;
			$row['code'] = $value->code;
			$row['start_date'] = $this->toFormat($value->start_date, "Y-m-d");
			$row['end_date'] =  $this->toFormat($value->end_date, "Y-m-d");
			$row['projects_code'] = $value->projects_code;
			$vat = ($value->ppn == 1) ? "PPn" : "Non PPn";
			$row['ppn'] = $vat;
			$row['actions'] = '<a href=invoice/print_wo/'.$value->id.'><button class="btn btn-sm btn-success" type="button"><i class="fa fa-print"></i></button></a>
			<button class="btn btn-sm btn-info" onclick="edit('.$value->id.')" type="button"><i class="fa fa-edit"></i></button>
							   <button class="btn btn-sm btn-danger" onclick="remove('.$value->id.')" type="button"><i class="fa fa-trash"></i></button>';
			$data[] = $row;
			$count++;
		}

		$result['data'] = $data;

		echo json_encode($result);
	}

	function add(){
		$so_status = $this->pdm->check_project_details($this->input->post('projects_id'));
		if ($so_status != null) {
			$temp = explode("/",$this->input->post('code'));
			$type = explode("-", $temp[1]);
			$vat = ( $type[1] == "P" ) ? 1 : 0 ;
			$data = array(
				'ppn' => $vat,
				'code' => $this->input->post('code'),
				'start_date' =>$this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'projects_id' => $this->input->post('projects_id'),
			);
			$data = $this->add_adding_detail($data);
			$inserted = $this->wom->add_id($data);
			if($inserted){
				$detail = $this->pdm->get_project_details( $this->input->post('projects_id'));
				$status = $this->wodm->add_wo_details($inserted, $detail);
			}
			echo json_encode(array('id' => $inserted));
		} else {
			echo json_encode(array('fail' => "fail"));
		}
	}

	function get_by_id($id){
		$detail = $this->wom->get_detail($id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'start_date' =>$this->input->post('start_date'),
			'end_date' => $this->input->post('end_date')
		);
		$data = $this->add_updating_detail($data);
		$status = $this->wom->update_id('id', $this->input->post('change_id'), $data);
		echo json_encode(array('id' => $status));
	}

	function delete($id){        
		$status = $this->wom->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id = -1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->wodm->get_work_order_details($id);
			$data = array();
			$count = 1;
			foreach($result as $value){
				$row = array();
				$row['no'] = $count;
				$row['id'] = $value->id;
				$row['name'] = $value->name;
				$row['qty'] = $value->qty;
				$row['symbol'] = $value->symbol;
				$row['note'] = $value->note;
				$row['action'] = '<a href=product_movement_detail/details/'.$value->woid.'/'.$value->pid.'><button class="btn btn-sm btn-info" type="button">Details</button></a>';
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "POST":
			$data = array(
				'qty' => $this->input->post('qty'),
				'products_id' => $this->input->post('products_id'),
				'projects_id' => $id
			);
			$insert = $this->pdm->add_id($data);

			$row = array();
			$row['id'] = $insert;
			$row['products_id'] = $this->input->post('products_id');
			$row['qty'] = $this->input->post('qty');

			echo json_encode($row);
			break;

			case "PUT":
			$this->input->raw_input_stream;
			$data = array(
				'qty' => $this->input->input_stream('qty'),
				'products_id' => $this->input->input_stream('products_id')
			);
			$result = $this->pdm->update('id',$this->input->input_stream('id'),$data);
			break;

			case "DELETE":
			$this->input->raw_input_stream;
			$status = $this->pdm->delete('id', $this->input->input_stream('id'));
			break;
		}
	}

	public function update_detail(){
		$data = array(
			'qty' =>$this->input->post('qty')
		);
		$status = $this->wodm->update('id', $this->input->post('details_id'), $data);
		echo json_encode(array('status' => $status));
	}

	public function get_work_orders_by_month_year()
	{
		$result = $this->wodm->get_work_orders_by_month_year();
		$data = array();
		foreach($result as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['code'] = $value->code;
			$data[] = $row;
		}

		$result = $data;
		echo json_encode($result);
	}

	public function get_product_by_month_year($wo_id)
	{
		$result = $this->wodm->get_product_by_month_year($wo_id);
		$data = array();
		foreach($result as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['code'] = $value->code;
			$row['name'] = $value->name;
			$data[] = $row;
		}

		$result = $data;
		echo json_encode($result);
	}

	public function populate_month_year($type)
	{
		$result = $this->wom->populate_month_year($type);
		$data = array();
		foreach($result as $value){
			$row = array();
			if($type == "month"){
				$row['value'] = date('F', mktime(0, 0, 0, $value->id, 10));
			}else{
				$row['value'] = $value->id;
			}
			$row['id'] = $value->id;
			$data[] = $row;
		}

		$result = $data;
		echo json_encode($result);
	}

}
