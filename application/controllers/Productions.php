<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productions extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('productions_model', 'pm');
		$this->load->model('production_details_model', 'pdm');
		$this->load->model('customers_model', 'cm');
	}
	
	private function get_column_attr(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('production_date', '', 'Production Date');            
		$table->addColumn('actions', '', 'Actions');        
		return $table->getColumns();
	}
	
	public function index()
	{
		$data['title'] = "ERP | Productions";
		$data['page_title'] = "Productions";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Production", "Productions");
		$data['page_view']  = "production/productions";		
		$data['js_asset']   = "productions";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	public function populate_product_select($id=-1){
		$result = $this->pdm->populate_product_select($id);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['Name'] = $value->value;
			$row['Id'] = $value->id;
			$data[] = $row;
			$count++;
		}

		$result = $data;
		echo json_encode($result);
	}

	public function populate_production_det_select($id=-1)
	{
		$result = $this->pdm->populate_production_det_select($id);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['Name'] = $value->value;
			$row['Id'] = $value->production_details_id."-".$value->products_id;
			$data[] = $row;
			$count++;
		}

		$result = $data;
		echo json_encode($result);
	}

	public function view_data(){
		$result = $this->pm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['production_date'] = $value->production_date;
			$row['actions'] = '<button class="btn btn-sm btn-info" onclick="edit('.$value->id.')" type="button"><i class="fa fa-edit"></i></button>
							   <button class="btn btn-sm btn-danger" onclick="remove('.$value->id.')" type="button"><i class="fa fa-trash"></i></button>';
			$data[] = $row;
			$count++;
		}

		$result['data'] = $data;

		echo json_encode($result);
	}

	function add(){
		$data = array(
			'production_date' => date("Y-m-d H:i:s", strtotime($this->input->post('production_date'))),
			'created_at' => date("Y-m-d H:i:s")
		);
		$inserted = $this->pm->add_id($data);
		echo json_encode(array('id' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->pm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('name')),
			'product_categories_id' => $this->input->post('product_categories_id')
		);
		$status = $this->pm->update_id('id', $this->input->post('change_id'), $data);
		echo json_encode(array('id' => $status));
	}

	function delete($id){        
		$status = $this->pm->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id = -1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->pdm->get_production_details($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['production_date'] = $value->production_date;
				$row['project_code'] = $value->project_code;
				$row['wo_id'] = $value->wo_id;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "POST":
			$data = array(
				'work_orders_id' => $this->input->post('wo_id'),
				'productions_id' => $id
			);
			$result = $this->pdm->add_id($data);

			$row = array();
			$row['id'] = $result;
			$row['production_date'] = "";
			$row['project_code'] = "";
			$row['wo_id'] = $this->input->post('wo_id');

			echo json_encode($row);
			break;

			case "PUT":
			$this->input->raw_input_stream;
			$data = array(
				'work_orders_id' => $this->input->input_stream('wo_id')
			);
			$result = $this->pdm->update('id',$this->input->input_stream('id'),$data);
			break;

			case "DELETE":
			$this->input->raw_input_stream;
			$status = $this->pdm->delete('id', $this->input->input_stream('id'));
			break;
		}
	}


}
