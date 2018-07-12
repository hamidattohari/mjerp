<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	function  __construct() {
		parent::__construct();
			
	}
                
	public function index()
	{
		$data['title'] = "ERP | Dashboard";
		$data['page_title'] = "Dashboard";
		$data['breadcumb']  = array("Dashboard");
		$data['page_view']  = "dashboard";		
		$data['js_asset']   = "dashboard";	
		$data['csrf'] = $this->csrf;
		$data['menu'] = $this->get_menu();
		$this->add_history($data['page_title']);							
		$this->load->view('layouts/master', $data);
	}
}
