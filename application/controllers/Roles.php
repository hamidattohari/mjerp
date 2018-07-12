<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('roles_model', 'rm');
			$this->load->model('previllage_model', 'pm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('name', '', 'Roles');        
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | Roles";
		$data['page_title'] = "Roles";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Setting", "Roles");
		$data['page_view']  = "settings/roles";		
		$data['js_asset']   = "roles";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->rm->get_output_data();
        $data = array();
        $count = 0;
        foreach($result['data'] as $value){
            $row = array();
            $row['id'] = $value->id;
			$row['name'] = $value->name;
			$row['actions'] = '<button class="btn btn-sm btn-info" onclick="edit('.$value->id.')" type="button"><i class="fa fa-edit"></i></button>
							  .<button class="btn btn-sm btn-danger" onclick="remove('.$value->id.')" type="button"><i class="fa fa-trash"></i></button>';
            $data[] = $row;
            $count++;
        }

        $result['data'] = $data;

        echo json_encode($result);
	}

	function add(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('name'))
		);
		$id = $this->rm->add_id($data);
		$inserted = $this->pm->add_role_previllage($id);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->rm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('name'))
		);
		$status = $this->rm->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->rm->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

}
