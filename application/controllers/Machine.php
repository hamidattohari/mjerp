<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Machine extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('machine_model', 'mm');
			$this->load->model('processes_model', 'pm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
        $table->addColumn('code', '', 'Code');
        $table->addColumn('process', '', 'Process');        
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | Machine";
		$data['page_title'] = "Machine";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Master", "Machine");
		$data['page_view']  = "master/machine";		
		$data['js_asset']   = "machine";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();					
		$data['process'] = $this->pm->get_all_data();	
		$this->add_history($data['page_title']);				
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->mm->get_output_data();
        $data = array();
        $count = 0;
        foreach($result['data'] as $value){
            $row = array();
            $row['id'] = $value->id;
			$row['code'] = $value->code;
			$row['process'] = $value->name;
			$row['actions'] = '<button class="btn btn-sm btn-info" onclick="edit('.$value->id.')" type="button"><i class="fa fa-edit"></i></button>
							  .<button class="btn btn-sm btn-danger" onclick="remove('.$value->id.')" type="button"><i class="fa fa-trash"></i></button>';
            $data[] = $row;
            $count++;
        }

        $result['data'] = $data;

        echo json_encode($result);
	}

	public function populate_select(){
		$result = $this->mm->get_all_data();
		$data = array();
		$count = 0;
		foreach($result as $value){
			if ($value->processes_id == $this->input->get('processes_id')) {
				$row = array();
				$row['code'] = $value->code;
				$row['processes_id'] = $value->processes_id;
				$data[] = $row;
				$count++;
			}
		}

		$result = $data;
		echo json_encode($result);
	}

	function add(){
		$data = array(
			'code' => strtoupper($this->input->post('code')),
			'processes_id' => ucfirst($this->input->post('processes_id')),			
		);
		$inserted = $this->mm->add($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->mm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'code' => strtoupper($this->input->post('code')),
			'processes_id' => ucfirst($this->input->post('processes_id')),
		);
		$status = $this->mm->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->mm->delete2('id', $id);
		echo json_encode(array('status' => $status));
	}

}
