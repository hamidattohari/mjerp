<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_categories extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('material_cat_model', 'mcm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('name', '', 'Category');        
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }

    public function get_material_categories(){
		$result = $this->mcm->get_all_data();
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
	
	public function index()
	{
		$data['title'] = "ERP | Material Categories";
		$data['page_title'] = "Material Categories";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Master", "Material Categories");
		$data['page_view']  = "master/material_categories";		
		$data['js_asset']   = "material-categories";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();		
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->mcm->get_output_data();
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
		$inserted = $this->mcm->add($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->mcm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('name'))
		);
		$status = $this->mcm->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->mcm->delete2('id', $id);
		echo json_encode(array('status' => $status));
	}

}
