<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('history_model', 'hm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('no', '', 'No');
        $table->addColumn('date', '', 'Date');
        $table->addColumn('name', '', 'Name');
        $table->addColumn('page', '', 'Page');        
        $table->addColumn('action', '', 'Action');        
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | User Log ";
		$data['page_title'] = "User Log";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Application", "User Log");
		$data['page_view']  = "settings/history";		
		$data['js_asset']   = "history";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();	
		$this->add_history($data['page_title']);								
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->hm->get_output_data();
        $data = array();
        $count = 1;
        foreach($result['data'] as $value){
            $row = array();
            $row['no'] = $count;
            $row['date'] = $value->date;
			$row['name'] = $value->name;
			$row['page'] = $value->page;
			$row['action'] = $value->actions;
			$data[] = $row;
            $count++;
        }

        $result['data'] = $data;

        echo json_encode($result);
	}

}
