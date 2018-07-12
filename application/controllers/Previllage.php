<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Previllage extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('roles_model', 'rm');
		$this->load->model('previllage_model', 'pm');
	}
				
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('name', '', 'Name');            
        return $table->getColumns();
    }

	public function index()
	{
		$data['title'] = "ERP | Privilege";
		$data['page_title'] = "Privilege";
		$data['table_title'] = "Roles";	
		$data['breadcumb']  = array("Setting", "Privilege");
		$data['page_view']  = "settings/previllage";		
		$data['js_asset']   = "previllage";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;
		$data['menu'] = $this->get_menu();		
		$data['roles'] = $this->rm->get_all_data();		
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	function get_all_menu($id_role = ""){
		$data = $this->pm->get_all_menu($id_role);
		echo json_encode($data);
	}

	function save_previllage(){
		$status = $this->pm->save_previllage();
		echo json_encode(array('status' => $status));
	}

}
