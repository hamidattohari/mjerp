<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usage_categories extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('usage_cat_model', 'ucm');
			$this->load->model('material_usage_cat_model', 'muc');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('name', '', 'Category');        
        $table->addColumn('code', '', 'Code');        
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | Usage Categories";
		$data['page_title'] = "Usage Categories";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Master", "Usage Categories");
		$data['page_view']  = "master/usage_categories";		
		$data['js_asset']   = "usage-categories";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();		
		$this->add_history($data['page_title']);				
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->ucm->get_output_data();
        $data = array();
        $count = 0;
        foreach($result['data'] as $value){
            $row = array();
            $row['id'] = $value->id;
			$row['name'] = $value->name;
			$row['code'] = $value->code;
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
			'name' => $this->normalize_text($this->input->post('name')),
			'code' => $this->input->post('code')
		);
		$inserted = $this->ucm->add_id($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->ucm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('name')),
			'code' => $this->input->post('code')
		);
		$status = $this->ucm->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->ucm->delete2('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id=-1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->muc->get_material_usage_categories($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['name'] = $value->name;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "POST":
			$data = array(
				'material_categories_id' => $this->normalize_text($this->input->post('name')),
				'usage_categories_id' => $id
			);
			$result = $this->muc->add_id($data);

			$row = array();
			$row['id'] = $result;
			$row['name'] = $this->input->post('name');

			echo json_encode($row);
			break;

			case "PUT":
			$this->input->raw_input_stream;
			$data = array(
				'material_categories_id' => $this->normalize_text($this->input->input_stream('name')),
				'usage_categories_id' => $id
			);
			$result = $this->muc->update('id',$this->input->post('id'),$data);
			break;

			case "DELETE":
			$this->input->raw_input_stream;
			$status = $this->muc->delete('id', $this->input->input_stream('id'));
			break;
		}
	}

}
