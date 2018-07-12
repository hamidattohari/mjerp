<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->model('application_model', 'am');
	}
	
	public function index()
	{
		$data['title'] = "ERP | Company Info";
		$data['page_title'] = "Company Info";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Setting", "Application");
		$data['page_view']  = "settings/application";		
		$data['js_asset']   = "application";
		$data['info'] = $this->am->get_info();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();	
		$this->add_history($data['page_title']);				
		$this->load->view('layouts/master', $data);
	}

	function update(){
		$data = array(
			$this->input->post('name') => $this->input->post('value'),
		);
		$status = $this->am->update('id', $this->input->post('pk'), $data);
		$msg  = "";
		if(!$status){
			$msg = "Update failed!";
		}
		echo json_encode(array('status' => $status, 'msg' => $msg));
   }

   function get_logo(){
	   $result = $this->am->get_logo();
	   echo json_encode($result);
   }

   function update_logo(){

		$param = array(
			'file_name' => 'company_logo',
			'folder' => 'images',
			'field' => 'logo_path',
		);

		$result = $this->upload_file($param);
		$status = $result['status'];
	
		if($result['status']){
			$data = array(
				'logo_path' => $result['msg']
			);
			$status = $this->am->update('id', 1, $data);
		}

		echo json_encode(array('status' => $status)); 

   }


   function update_logo_title(){

	$param = array(
		'file_name' => 'company_logo_title',
		'folder' => 'images',
		'field' => 'logo_title_path',
	);

	$result = $this->upload_file($param);
	$status = $result['status'];

	if($result['status']){
		$data = array(
			'logo_title_path' => $result['msg']
		);
		$status = $this->am->update('id', 1, $data);
	}

	echo json_encode(array('status' => $status)); 

}
   
}
