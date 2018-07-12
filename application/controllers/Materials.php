<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materials extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('materials_model', 'mm');
			$this->load->model('material_cat_model', 'mcm');
			$this->load->model('vendors_model', 'vm');
			$this->load->model('uom_model', 'um');
			$this->load->model('material_vendor_model', 'mvm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('name', '', 'Name');        
        $table->addColumn('category', '', 'Category');        
        $table->addColumn('min_stock', 'right', 'Min Stock');        
        $table->addColumn('uom', '', 'Unit');              
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | Materials";
		$data['page_title'] = "Materials";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Master", "Materials");
		$data['page_view']  = "master/materials";		
		$data['js_asset']   = "materials";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();					
		$data['m_categories'] = $this->mcm->get_all_data();							
		$data['uom'] = $this->um->get_all_data();		
		$this->add_history($data['page_title']);						
		$this->load->view('layouts/master', $data);
	}

	public function populate_select(){
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

	public function populate_select_per_vendor($id){
		$result = $this->mm->get_materials_per_vendor($id);
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

	public function populate_autocomplete(){
		$result = $this->mm->populate_autocomplete();
		$data = array();
		foreach($result as $value){
			$row = array();
			$row['value'] = $value->name;
			$row['id'] = $value->id;
			$data[] = $row;
		}

		$result = $data;
		echo json_encode($result);
	}

	public function view_data(){
		$result = $this->mm->get_output_data();
        $data = array();
        $count = 0;
        foreach($result['data'] as $value){
            $row = array();
            $row['id'] = $value->id;
			$row['name'] = $value->name;
			$row['category'] = $value->category;
			$row['min_stock'] = $value->min_stock;
			$row['uom'] = $value->uom;
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
			'name' => $this->input->post('name'),
			'material_categories_id' => $this->input->post('material_categories_id'),
			'uom_id' => $this->input->post('uom_id'),
			'min_stock' => $this->input->post('min_stock'),
			'initial_qty' => $this->input->post('initial_qty'),
			'created_at' => date("Y-m-d H:m:s")
		);
		$inserted = $this->mm->add_id($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->mm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->input->post('name'),
			'material_categories_id' => $this->input->post('material_categories_id'),
			'uom_id' => $this->input->post('uom_id'),
			'min_stock' => $this->input->post('min_stock'),
			'initial_qty' => $this->input->post('initial_qty'),
			'updated_at' => date("Y-m-d H:m:s")
		);
		$status = $this->mm->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->mm->delete2('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id=-1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->mvm->get_material_vendor($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['vendors_id'] = $value->vendors_id;
				$row['name'] = $value->name;
				$row['address'] = $value->address;
				$row['telp'] = $value->telp;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "DELETE":
			$this->input->raw_input_stream;
			$status = $this->mvm->delete('id', $this->input->input_stream('id'));
			break;
		}
	}

	public function add_material_vendor()
	{
		$data = array(
			'materials_id' => $this->input->post('materials_id'),
			'vendors_id' => $this->input->post('vendors_id')
		);
		$inserted = $this->mvm->add($data);
		echo json_encode(array('status' => $inserted));
	}

	public function edit_material_vendor()
	{
		$data = array(
			'vendors_id' => $this->input->post('vendors_id')
		);
		$status = $this->mvm->update('id', $this->input->post('details_id'), $data);
		echo json_encode(array('status' => $status));
	}

}
