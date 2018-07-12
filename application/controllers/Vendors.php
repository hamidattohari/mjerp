<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('vendors_model', 'vm');
			$this->load->model('materials_model', 'mm');
			$this->load->model('material_vendor_model', 'mvm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        // $table->addColumn('id', '', 'ID');
		$table->addColumn('name', '', 'Name');
        $table->addColumn('description', '', 'Description');        
        $table->addColumn('address', '', 'Address');        
        $table->addColumn('telp', '', 'Telp');        
        $table->addColumn('vat', '', 'VAT');        
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | Vendors";
		$data['page_title'] = "Vendors";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Master", "Vendors");
		$data['page_view']  = "master/vendors";		
		$data['js_asset']   = "vendors";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();		
		$this->add_history($data['page_title']);				
		$this->load->view('layouts/master', $data);
	}

	public function populate_autocomplete(){
		$result = $this->vm->populate_autocomplete();
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

	public function populate_autocomplete2(){
		$result = $this->vm->populate_autocomplete2();
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

	public function populate_autocomplete_material(){
		$result = $this->mvm->populate_autocomplete_material();
		$data = array();
		foreach($result as $value){
			$row = array();
			$row['value'] = $value->name;
			$row['id'] = $value->materials_id;
			$data[] = $row;
		}

		$result = $data;
		echo json_encode($result);
	}

	public function view_data(){
		$result = $this->vm->get_output_data();
        $data = array();
        $count = 0;
        foreach($result['data'] as $value){
            $row = array();
            // $row['id'] = $value->id;
			$row['name'] = $value->name;
			$row['description'] = $value->description;
			$row['address'] = $value->address;
			$row['telp'] = $value->telp;
			$vat = "PPn";
			if($value->ppn == 0){
				$vat = "Non PPn";
			}
			$row['vat'] = $vat;
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
			'description' => $this->input->post('description'),
			'address' => $this->input->post('address'),
			'telp' => $this->input->post('telp'),
			'ppn' => $this->input->post('vat'),
			'created_at' => date("Y-m-d H:m:s")
		);
		$inserted = $this->vm->add($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->vm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'address' =>$this->input->post('address'),
			'telp' => $this->input->post('telp'),
			'ppn' => $this->input->post('vat'),
			'updated_at' => date("Y-m-d H:m:s")
		);
		$status = $this->vm->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->vm->delete2('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id = -1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->mvm->get_vendor_material($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['name'] = $value->name;
				$row['category'] = $value->category;
				$row['min_stock'] = $value->min_stock;
				$row['uom'] = $value->symbol;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "POST":
			$data = array(
				'name' => $this->input->post('name'),
				'material_categories_id' => $this->input->post('category'),
				'min_stock' => $this->input->post('min_stock'),
				'uom_id' => $this->input->post('uom'),
				'vendors_id' => $id
			);
			$result = $this->mm->add_id($data);

			$row = array();
			$row['id'] = $result;
			$row['name'] = $this->input->post('name');
			$row['category'] = $this->input->post('category');
			$row['min_stock'] = $this->input->post('min_stock');
			$row['uom'] = $this->input->post('uom');

			echo json_encode($row);
			break;

			case "PUT":
			$this->input->raw_input_stream;
			$data = array(
				'name' => $this->input->input_stream('name'),
				'material_categories_id' => $this->input->input_stream('category'),
				'min_stock' => $this->input->input_stream('min_stock'),
				'uom_id' => $this->input->input_stream('uom')
			);
			$result = $this->mm->update('id',$this->input->input_stream('id'),$data);
			break;

			case "DELETE":
			$this->input->raw_input_stream;
			$status = $this->mm->delete('id', $this->input->input_stream('id'));
			break;
		}
	}

}
