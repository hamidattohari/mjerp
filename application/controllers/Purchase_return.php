<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('receive_model', 'rm');
		$this->load->model('shipping_details_model', 'sdm');
		$this->load->model('purchase_return_model', 'prm');
		$this->load->model('purchase_return_detail_model', 'prdm');
		$this->load->model('receive_model', 'rcv');
		$this->load->model('receive_det_model', 'rcvd');
		$this->load->model('material_inventory_model', 'mi');
	}
	
	private function get_column_attr(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('code', '', 'Code');  
		$table->addColumn('r_code', '', 'Receiving Code');       
		$table->addColumn('date', '', 'Return Date');               
		$table->addColumn('actions', '', 'Actions');        
		return $table->getColumns();
	}
	
	public function index()
	{
		$data['title'] = "ERP | Purchase Return";
		$data['page_title'] = "Purchase Return";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Purchasing", "Purchase Return");
		$data['page_view']  = "purchasing/return";		
		$data['js_asset']   = "purchase_return";	
		$data['columns']    = $this->get_column_attr();	
		$data['receiving'] = $this->rm->get_all_data();	
		$data['csrf'] = $this->csrf;			
		$data['menu'] = $this->get_menu();	
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	public function generate_id(){
		$id = $this->prm->generate_id();
		echo json_encode(array('id' => $id));
	}

	public function populate_product_select($id=-1){
		$result = $this->sm->populate_product_select($id);
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

	public function view_data(){
		$result = $this->prm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['code'] = $value->code;
			$row['r_code'] = $value->r_code;
			$row['date'] = $value->date;
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
			'code' => $this->input->post('code'),			
			'date' => $this->to_mysql_date($this->input->post('purchase_return_date')),
			'receiving_id' => $this->input->post('receiving_id'),
		);
		$data = $this->add_adding_detail($data);
		$inserted = $this->prm->add_id($data);

		$receive_details = $this->rcvd->get_receive_details($this->input->post('receiving_id'));
		foreach($receive_details as $value){
			$data = array(
				'qty' => $value->qty,
				'price' => $value->unit_price,
				'total_price' => $value->total_price,
				'materials_id' => $value->materials_id,
				'return_id' => $inserted,
			);
			$insert = $this->prdm->add_id($data);

			$data2 = array(
				'date' => $this->mysql_time_now(),
				'type' => 'out',
				'p_return_details_id' => $insert,
				'qty' => $value->qty,
				'materials_id' => $value->materials_id 
			);

			$this->mi->add_id($data2);
		}

		echo json_encode(array('id' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->prm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(			
			'date' => $this->to_mysql_date($this->input->post('purchase_return_date')),
			'receiving_id' => $this->input->post('receiving_id'),
		);
		$data = $this->add_updating_detail($data);
		$status = $this->prm->update_id('id', $this->input->post('change_id'), $data);
		echo json_encode(array('id' => $status));
	}

	function delete($id){        
		//$status = $this->prm->delete('id', $id);
		echo json_encode(array('status' => false));
	}

	function jsgrid_functions($id = -1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->prdm->get_purchase_return_details($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['materials_id'] = $value->materials_id;
				$row['qty'] = $value->qty;
				$row['note'] = $value->note;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "POST":
			$data = array(
				'materials_id' => $this->input->post('materials_id'),
				'qty' => $this->input->post('qty'),
				'note' =>$this->input->post('note'),
				'return_id' => $id
			);
			$insert = $this->prdm->add_id($data);

			$row = array();
			$row['id'] = $insert;
			$row['materials_id'] = $this->input->post('materials_id');
			$row['qty'] = $this->input->post('qty');
			$row['note'] = $this->input->post('note');
			echo json_encode($row);

			$data2 = array(
				'date' => $this->mysql_time_now(),
				'type' => 'out',
				'p_return_details_id' => $insert,
				'qty' => $this->input->post('qty'),
				'materials_id' => $this->input->post('materials_id')
			);

			$this->mi->add_id($data2);
			break;

			case "PUT":
			$this->input->raw_input_stream;
			$data = array(
				'qty' => $this->input->input_stream('qty'),
				'note' =>$this->input->input_stream('note'),
				'materials_id' => $this->input->input_stream('materials_id')
			);
			$result = $this->prdm->update('id',$this->input->input_stream('id'),$data);

			$row = array();
			$row['id'] = $this->input->input_stream('id');
			$row['materials_id'] = $this->input->input_stream('materials_id');
			$row['qty'] = $this->input->input_stream('qty');
			$row['note'] = $this->input->input_stream('note');
			echo json_encode($row);

			$data2 = array(
				'date' => $this->mysql_time_now(),
				'qty' => $this->input->input_stream('qty')
			);

			$this->mi->update('p_return_details_id',$this->input->input_stream('id'),$data2);
			break;

			case "DELETE":
			$this->input->raw_input_stream;
			$status = $this->mi->delete('p_return_details_id', $this->input->input_stream('id'));
			$status = $this->prdm->delete('id', $this->input->input_stream('id'));
			break;
		}
	}


}
