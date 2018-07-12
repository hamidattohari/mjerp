<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_nonmaterial extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('nonmaterial_usage_model', 'nmu');
			$this->load->model('nonmaterial_usage_det_model', 'nmud');
			$this->load->model('material_usage_cat_model', 'muc');
			$this->load->model('material_inventory_model', 'mi');
			$this->load->model('materials_model', 'mtm');
			$this->load->model('work_orders_model', 'wom');
			$this->load->model('product_materials_model', 'pmm');
			$this->load->model('products_model', 'pm');
			$this->load->model('unfinished_product_inventory_model', 'upi');

	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
		$table->addColumn('date', '', 'Date');            
		$table->addColumn('code_pick', '', 'Pick Code');            
		$table->addColumn('code_return', '', 'Return Code');            
		$table->addColumn('wocode', '', 'WO Code');            
		$table->addColumn('name', '', 'Product');            
		$table->addColumn('actions', '', 'Actions');       
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | Return Non Materials";
		$data['page_title'] = "Return Non Materials";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Production", "Return Non Materials");
		$data['page_view']  = "production/returnnm";		
		$data['js_asset']   = "returnnm";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;
		$data['menu'] = $this->get_menu();		
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	public function details($woid,$pid)
	{
		$pname = $this->pm->get_by_id('id',$pid);
		$data['title'] = "ERP | Return Non Materials";
		$data['page_title'] = "Return Non Materials <strong>".$pname->name."</strong>";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Production", "Return Non Materials");
		$data['page_view']  = "production/returnnm";		
		$data['js_asset']   = "returnnm";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;
		$data['menu'] = $this->get_menu();
		$data['woid'] = $woid;					
		$data['pid'] = $pid;		
		$this->add_history($data['page_title']);			
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->nmu->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['date'] = $this->toFormat($value->date, "Y-m-d");
			$row['code_pick'] = $value->code_pick;
			$row['code_return'] = $value->code_return;
			$row['wocode'] = $value->wocode;
			$row['name'] = $value->name;
			$row['actions'] = '<button class="btn btn-sm btn-info" type="button" onclick="printEvidence('.$value->id.')"><i class="fa fa-print"></i></button>
			<button class="btn btn-sm btn-info" onclick="edit('.$value->id.')" type="button"><i class="fa fa-edit"></i></button>
							  .<button class="btn btn-sm btn-danger" onclick="remove('.$value->id.')" type="button"><i class="fa fa-trash"></i></button>';
            $data[] = $row;
			$count++;
		}

		$result['data'] = $data;

		echo json_encode($result);
	}

	function add(){
		$data = array(
			'return_date' => $this->normalize_text($this->input->post('return_date')),
			'code' => $this->input->post('code'),
			'material_usage_id' => $this->input->post('asd'),
			'created_at' => date("Y-m-d H:m:s")
		);
		$inserted = $this->mr->add_id($data);
		echo json_encode(array('id' => $inserted));
	}

	function get_by_id($id){
		$data = array();
		$detail = $this->nmu->get_by_id('id',$id);
		$data['detail'] = $detail;
		// $status = $this->mr->have_material_return('material_usage_id', $id);
		// $data['status'] = $status;
		echo json_encode($data);
	}

	function update(){
		$data = array(
			'return_date' => $this->normalize_text($this->input->post('return_date')),
			'code' => $this->input->post('code'),
			'material_usage_id' => $this->input->post('asd'),
			'updated_at' => date("Y-m-d H:m:s")
		);
		$status = $this->mr->update_id('id', $this->input->post('change_id'), $data);
		echo json_encode(array('id' => $status));
   }

	function delete($id){        
		$status = $this->mr->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->nmud->get_nonmaterial_return_details($id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['products'] = $value->products_id;
				$row['name'] = $value->name;
				$row['qty'] = $value->qty;
				$row['note'] = $value->note;
				$row['symbol'] = $value->symbol;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "DELETE":
			$status = $this->nmud->delete('id', $this->input->post('id'));
			break;
		}
	}


	public function update_detail(){

		$material_usage_id = $this->nmud->get_by_id('id',$this->input->post('details_id'));
		$code_return = $this->nmu->generate_return_id();
		$data1 = array(
			'code_return' => $code_return
		);
		$status = $this->nmu->update_id('id', $material_usage_id->material_usages_id, $data1);

		$data = array(
			'return_note' => $this->normalize_text($this->input->post('note')),
			'qty_return' => $this->input->post('qty')
		);
		$status = $this->nmud->update_id('id',$this->input->post('details_id'),$data);
		$detail = $this->nmud->get_by_id('id',$this->input->post('details_id'));
		if(isset($status)){
			$status = $this->upi->material_usage_change($this->input->post('details_id'), $this->input->post('materials_id'), $detail, "in");
		}
		echo json_encode(array('status'=> $status));
	}

	public function add_new_material(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('material_name')),
			'material_categories_id' => $this->input->post('material_categories_id'),
			'uom_id' => $this->input->post('uom_id'),
			'min_stock' => 0,
			'created_at' => date("Y-m-d H:m:s")
		);
		$id = $this->mtm->add_id($data);
		$data = array(
			'products_id' => $this->input->post('products_id'),
			'materials_id' => $id,
			'qty' => 0,
		);
		$status = $this->pmm->add($data);
		$data = array(
			'qty_pick' => 0,
			'qty_return' => $this->input->post('return_qty'),
			'return_note' => $this->input->post('return_note'),
			'materials_id' => $id,
			'material_usages_id' => $this->input->post('material_usages_id'),
		);
		$status = $this->nmud->add_id($data);
		$detail = $this->nmud->get_by_id('id',$status);
		if(isset($status)){
			$status = $this->upi->material_usage_change($status, $this->input->post('materials_id'), $detail);
		}
		echo json_encode(array('status'=> $status));
	}

}
