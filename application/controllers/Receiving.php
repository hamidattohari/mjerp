<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('purchase_model', 'prc');
		$this->load->model('purchase_det_model', 'prd');
		$this->load->model('materials_model', 'mm');
		$this->load->model('vendors_model', 'vd');
		$this->load->model('receive_model', 'rcv');
		$this->load->model('receive_det_model', 'rcvd');
		$this->load->model('material_inventory_model', 'mi');
	}
	

	private function get_column_attr(){
		$table = new TableField();
		$table->addColumn('id', '', 'Id');
		$table->addColumn('code', '', 'Kode');
		$table->addColumn('delivery_date', '', 'Date');        
		$table->addColumn('vat', '', 'Vat');        
		$table->addColumn('vendor', '', 'Vendor');        
		$table->addColumn('percentage', '', 'Percentage');        
		$table->addColumn('actions', '', 'Actions');        
		return $table->getColumns();
	}

	private function get_column_attr1(){
		$table = new TableField();
		$table->addColumn('id', '', 'Id');
		$table->addColumn('code', '', 'Kode');     
		$table->addColumn('receive_date', '', 'Receive Date');        
		$table->addColumn('actions', '', 'Actions');        
		return $table->getColumns();
	}

	public function get_materials(){
		$result = $this->mm->get_all_data();
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
	
	public function populate_receiving_details($id=-1){
		$result = $this->rcvd->populate_receiving_details($id);
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

	public function index()
	{
		$data['title'] = "ERP | Receiving List";
		$data['page_title'] = "Receiving";
		$data['table_title'] = "List Purchase Order";		
		$data['table_title1'] = "List Receiving";		
		$data['breadcumb']  = array("Master", "Receiving List");
		$data['page_view']  = "purchasing/receiving";		
		$data['js_asset']   = "receiving";	
		$data['columns']    = $this->get_column_attr();	
		$data['columns1']    = $this->get_column_attr1();	
		$data['csrf'] = $this->csrf;
		$data['menu'] = $this->get_menu();							
		$data['vendors'] = $this->vd->get_all_data();	
		$this->add_history($data['page_title']);
		$this->load->view('layouts/master', $data);
	}

	public function generate_id(){
		$id = $this->rcv->generate_id();
		echo json_encode(array('id' => $id));
	}

	public function view_data($id){
		$result = $this->rcv->get_where_id('purchasing_id',$id);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['id'] = $value->purchasing_id;
			$row['code'] = $value->code;
			$row['receive_date'] = $this->toFormat($value->receive_date, "Y-m-d");
			$row['actions'] = '<button class="btn btn-sm btn-default" onclick="details('.$value->id.')" type="button">details</button> <a href=invoice/print_receiving/'.$value->id.'><button class="btn btn-sm btn-success" target="_blank" type="button">print</button></a>';
			$data[] = $row;
			$count++;
		}

		$result = $data;

		echo json_encode(array('data' => $result));
	}

	function add($id){
		$purchase_data = $this->prc->get_by_id('id',$id);

		$data = array(
			'purchasing_id' => $id,
			'code' => $this->input->post('code'),
			'no_sj' => $this->input->post('no_sj'),
			'receive_date' => $this->input->post('receive_date'),
			'currency_id' => $purchase_data->currency_id,
			'created_at' => date('Y-m-d H:i:s'),
		);
		$inserted = $this->rcv->add_id($data);

		$purchase_details = $this->prd->get_purchase_details($id);
		foreach($purchase_details as $value){
			$subtotal = ($value->price*$value->qty);
			$vat = $subtotal*0.1;
			$total =$subtotal+$vat;
			$data = array(
				'qty' => $value->qty-$value->rqty,
				'unit_price' => $value->price,
				'discount' => 0,
				'total_price' => $total,
				'receiving_id' => $inserted,
				'materials_id' => $value->materials_id,
			);
			$insert = $this->rcvd->add_id($data);

			$data2 = array(
				'date' => $this->mysql_time_now(),
				'type' => 'in',
				'receive_details_id' => $insert,
				'qty' => $value->qty,
				'materials_id' => $value->materials_id 
			);

			$this->mi->add_id($data2);
		}

		echo json_encode(array('id' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->rcv->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function get_receiving($id){
		$detail = $this->rcv->get_receiving($id);
		echo json_encode($detail);
	}

	function update($id){
		$purchase_data = $this->prc->get_by_id('id',$id);
		$data = array(
			'code' => $this->input->post('code'),
			'receive_date' => $this->input->post('receive_date'),
			'currency_id' => $purchase_data->currency_id,
			'created_at' => date('Y-m-d H:i:s')
		);
		$status = $this->rcv->update_id('id', $this->input->post('change_id'), $data);
		echo json_encode(array('id' => $status));
	}

	function delete($id){        
		$status = $this->rcv->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->rcvd->get_receive_details($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['name'] = $value->id_materials;
				$row['qty'] = $value->qty;
				$row['price'] = $value->unit_price;
				$row['discount'] = $value->discount;
				$subtotal = ($value->unit_price*$value->qty)-$value->discount;
				$row['subtotal_price'] = $subtotal;
				$row['vat'] = $subtotal*0.1;
				$row['total_price'] =$subtotal+($subtotal*0.1);
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			// case "POST":
			// $data = array(
			// 	'materials_id' => $this->normalize_text($this->input->post('name')),
			// 	'qty' => $this->normalize_text($this->input->post('qty')),
			// 	'unit_price' => $this->normalize_text($this->input->post('price')),
			// 	'purchasing_id' => $id
			// );
			// $result = $this->prd->add($data);

			// $row = array();
			// $row['id'] = $insert;
			// $row['name'] = $this->input->post('name');
			// $row['qty'] = $this->input->post('qty');
			// $row['price'] = $this->input->post('price');

			// echo json_encode($row);
			// break;

			case "PUT":
			$this->input->raw_input_stream;
			$data = array(
				// 'materials_id' => $this->normalize_text($this->input->post('name')),
				'qty' => $this->input->input_stream('qty'),
				'unit_price' => $this->input->input_stream('price'),
				'discount' => $this->input->input_stream('discount'),
				'total_price' => $this->input->input_stream('price')*$this->input->input_stream('qty')
			);
			$result = $this->rcvd->update('id',$this->input->input_stream('id'),$data);

			$row = array();
			$row['id'] = $this->input->input_stream('id');
			$row['name'] = $this->input->input_stream('name');
			$row['qty'] = $this->input->input_stream('qty');
			$row['price'] = $this->input->input_stream('price');
			$row['discount'] = $this->input->input_stream('discount');
			$subtotal = ($this->input->input_stream('price')*$this->input->input_stream('qty'))-$this->input->input_stream('discount');
			$row['vat'] = $subtotal*0.1;
			$row['subtotal_price'] = $subtotal;
			$row['total_price'] = $subtotal+($subtotal*0.1);

			echo json_encode($row);

			$data2 = array(
				'date' => $this->mysql_time_now(),
				'qty' => $this->input->input_stream('qty')
			);

			$this->mi->update('receive_details_id',$this->input->input_stream('id'),$data2);
			break;

			case "DELETE":
			$status = $this->mi->delete('receive_details_id', $this->input->input_stream('id'));
			$status = $this->rcvd->delete('id', $this->input->input_stream('id'));
			break;
		}
	}

	public function upload_doc()
	{
		$param = array(
			'folder' => "delivery doc",
			'file_name' => $this->input->post('id_rec'),
			'field' => "doc_path"
		);	
		$upload = $this->upload_file($param);
		if($upload['status'] == true){
			$data = array(
				'doc_path' => $upload['msg']
			);
			$status = $this->rcv->update_id('id', $this->input->post('id_rec'), $data);
		}
		echo json_encode($upload);
	}

	public function download_doc($id=-1)
	{
		$detail = $this->rcv->get_by_id('id', $id);
		if(isset($detail) && $detail->doc_path != NULL ){
			$this->load->helper('download');
			force_download('./assets/'.$detail->doc_path, NULL);
		}else{
			echo "Wups, you haven't uploaded a document for this receiving yet.";
		}
	}

}
