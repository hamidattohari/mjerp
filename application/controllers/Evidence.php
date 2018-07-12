<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evidence extends MY_Controller {

	function  __construct() {
		parent::__construct();
			//$this->load->model('Login', 'login');
		$this->load->model('purchase_model', 'prc');
		$this->load->model('receive_model', 'rcv');
		$this->load->model('receive_det_model', 'rcvd');
		$this->load->model('materials_model', 'mm');
		$this->load->model('vendors_model', 'vm');
		$this->load->model('customers_model', 'cm');
		$this->load->model('shipping_model', 'sm');
		$this->load->model('shipping_details_model', 'sdm');
		$this->load->model('projects_model', 'prm');
		$this->load->model('work_orders_model', 'wom');		
		$this->load->model('project_details_model', 'pdm');
	}
                
	public function print_pickup_material($id)
	{
		$data['so'] = $this->prm->get_by_id('id', $id);
		$data['so_detail'] = $this->pdm->get_project_details($id);

		$customer_id = $data['so']->customers_id;
		$data['customer'] = $this->cm->get_by_id('id', $customer_id);

		$data['title'] = "ERP | Evidence";
		$data['page_title'] = "Evidence";
		$data['breadcumb']  = array("Evidence");
		$data['page_view']  = "evidence/pickup_evidence";		
		$data['js_asset']   = "evidence";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	
}
