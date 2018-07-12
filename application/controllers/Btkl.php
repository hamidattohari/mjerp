<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Btkl extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->model('btkl_model', 'bm');
	}

	function add(){
		$data = array(
			'processes_id' => $this->input->post('process'),
			'qty' => $this->input->post('qty'),			
			'price' => $this->input->post('price'),
			'hpp_id' => $this->input->post('hpp_id'),
		);
		$inserted = $this->bm->add($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->bm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'processes_id' => $this->input->post('process'),
			'qty' => $this->input->post('qty'),			
			'price' => $this->input->post('price')
		);
		$status = $this->bm->update('id', $this->input->post('details_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->bm->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id=-1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->bm->get_btkl_list($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['processes_id'] = $value->processes_id;
				$row['qty'] = $value->qty;
				$row['price'] = $value->price;
				$row['total_price'] = $value->qty*$value->price;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;
		}
	}

}
