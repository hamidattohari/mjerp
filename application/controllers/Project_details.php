<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_details extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('projects_model', 'pm');
		$this->load->model('project_details_model', 'pdm');
		$this->load->model('customers_model', 'cm');
	}


	public function view_data(){
		$result = $this->pdm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['name'] = $value->name;
			$row['qty'] = $this->formatNumber($value->qty);
			$row['uom'] = $value->symbol;
			$row['price'] = $this->formatCurrency($value->price);
			$row['total_price'] = $this->formatCurrency($value->total_price);
			$row['note'] = $value->note;
			$row['actions'] = '<button class="btn btn-sm btn-info" onclick="edit2('.$value->id.')" type="button"><i class="fa fa-edit"></i></button>
							   <button class="btn btn-sm btn-danger" onclick="remove2('.$value->id.')" type="button"><i class="fa fa-trash"></i></button>';
			$data[] = $row;
			$count++;
		}

		$result['data'] = $data;

		echo json_encode($result);
	}

	function add(){
		$data = array(
			'qty' => $this->input->post('qty'),			
			'price' => $this->input->post('price'),			
			'total_price' => $this->input->post('price')*$this->input->post('qty'),			
			'note' => $this->input->post('note'),						
			'projects_id' => $this->input->post('asd'),
			'products_id' => $this->input->post('products_id')
		);
		$inserted = $this->pdm->add_id($data);
		echo json_encode(array('id' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->pdm->get_project_details_by_id($id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'qty' => $this->input->post('qty'),			
			'price' => $this->input->post('price'),			
			'total_price' => $this->input->post('price')*$this->input->post('qty'),			
			'note' => $this->input->post('note'),		
			'products_id' => $this->input->post('products_id')
		);
		$status = $this->pdm->update_id('id', $this->input->post('details_id'), $data);
		echo json_encode(array('id' => $status));
	}

	function delete($id){        
		$status = $this->pdm->delete('id', $id);
		echo json_encode(array('status' => $status));
	}


}
