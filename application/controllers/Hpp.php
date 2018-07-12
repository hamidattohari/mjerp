<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hpp extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('usage_cat_model', 'uc');
			$this->load->model('material_usage_model', 'mu');
			$this->load->model('material_usage_cat_model', 'muc');
			$this->load->model('material_usage_det_model', 'mud');
			$this->load->model('material_inventory_model', 'mi');
			$this->load->model('work_orders_model', 'wom');
			$this->load->model('machine_model', 'mm');
			$this->load->model('hpp_model', 'hm');
			$this->load->model('material_cost_model', 'mc');
			$this->load->model('btkl_model', 'btklm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
		$table->addColumn('code', '', 'Code');            
		$table->addColumn('wocode', '', 'Work Order');           
		$table->addColumn('name', '', 'Product');            
		$table->addColumn('material_cost', '', 'Material Cost');            
		$table->addColumn('btkl', '', 'BTKL');            
		$table->addColumn('bop', '', 'BOP');            
		$table->addColumn('actions', '', 'Actions');       
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | HPP";
		$data['page_title'] = "HPP";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Production", "HPP");
		$data['page_view']  = "production/hpp";		
		$data['js_asset']   = "hpp";	
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;					
		$data['menu'] = $this->get_menu();		
		$this->add_history($data['page_title']);	
		$this->load->view('layouts/master', $data);
	}

	public function get_material_usage_details($id){
		$result = $this->mud->get_material_usage_details($id);
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
	
	public function generate_id(){
		$id = $this->mu->generate_id();
		echo json_encode(array('id' => $id));
	}

	public function view_data(){
		$result = $this->hm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$wo = $this->wom->get_by_id('code',$value->wocode);
			$row = array();
			$row['id'] = $value->id;
			$row['code'] = $value->code;
			$row['wocode'] = $value->wocode;
			$row['name'] = $value->name;
			$row['material_cost'] = $this->get_material_cost($value->id, $value->wocode);
			$row['btkl'] = $this->hm->get_total_btkl($value->month, $value->year);
			$row['bop'] = $this->hm->get_total_bop($value->id);
			$row['actions'] = '<button class="btn btn-sm btn-info" onclick="prints('.$value->id.')" type="button"><i class="fa fa-print"></i></button>
							  .<button class="btn btn-sm btn-info" onclick="edit('.$value->id.','.$wo->id.')" type="button"><i class="fa fa-edit"></i></button>
							  .<button class="btn btn-sm btn-danger" onclick="remove('.$value->id.')" type="button"><i class="fa fa-trash"></i></button>';
            $data[] = $row;
			$count++;
		}

		$result['data'] = $data;

		echo json_encode($result);
	}

	function add(){
		$code = $this->hm->generate_id();
		$data = array(
			'code' => $code,
			'month' => $this->input->post('month'),
			'year' => $this->input->post('year'),
			'products_id' => $this->input->post('products_id'),
			'work_orders_id' => $this->input->post('work_orders_id'),
			'penyusutan' => 0,
			'listrik' => 0,
			'lain_lain' => 0
		);
		$data = $this->add_adding_detail($data);
		$inserted = $this->hm->add_id($data);

		$result = $this->mc->get_material_cost($inserted, $this->input->post('work_orders_id'));
		foreach($result as $value){
			$unit_price = round($this->hm->get_per_pieces_price($value->id), 2);
			$data = array(
				'materials_id' => $value->id,
				'price' => $unit_price,
				'hpp_id' => $inserted
			);
			$this->mc->add_id($data);
		}

		echo json_encode(array('id' => $inserted, 'code' => $code));
	}

	function add_btkl(){
		$code = $this->btklm->generate_id();
		$data = array(
			'total' => $this->input->post('total'),
			'month' => $this->input->post('months1'),
			'year' => $this->input->post('years1'),
		);
		$data = $this->add_adding_detail($data);
		$inserted = $this->hm->add_id($data);

		echo json_encode(array('id' => $inserted, 'code' => $code));
	}

	function get_by_id($id){
		$detail = $this->hm->get_hpp_by_id($id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'code' => $code,
			'month' => $this->input->post('month'),
			'year' => $this->input->post('year'),
			'products_id' => $this->input->post('products_id'),
			'work_orders_id' => $this->input->post('work_orders_id'),
			'penyusutan' => 0,
			'listrik' => 0,
			'lain_lain' => 0
		);
		$status = $this->hm->update_id('id', $this->input->post('change_id'), $data);
		echo json_encode(array('id' => $status));
   }

	function delete($id){        
		$status = $this->hm->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($id=-1, $wo_id=-1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->mc->get_material_list($id, $wo_id);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['category'] = $value->category;
				$row['name'] = $value->name;
				$row['pick'] = $value->pick."(".$value->symbol.")";
				$row['used'] = $value->pick-$value->return."(".$value->symbol.")";
				$row['return'] = $value->return."(".$value->symbol.")";
				$row['unit_price'] = $value->price;
				$row['total_price'] = round(($value->pick-$value->return)*$value->price, 2);
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;
		}
	}

	function jsgrid_functions3($id=-1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$status = array('initital', 'intermediate', 'final', 'hold');
			$details = $this->hm->get_by_id('id', $id);
			$data = array();
			
			if(isset($details->products_id)){
				$wos = $this->hm->get_all_wos($details->products_id);
				$product_result = $this->hm->get_product_result($wos, $details->products_id);
				foreach($status as $value){
					$row = array();
					$row['description'] = ucfirst($value);
					$row['qty'] = $this->get_qty($value, $product_result);
					$row['unit'] = "roll";
					$row['pct'] = number_format(($this->get_pct($value, $product_result) * 100), 2, ".", ","). " %";
					$data[] = $row;
				}
			}

			$result = $data;
			echo json_encode($result);
			break;
		}
	}

	public function get_qty($val, $result)
	{
		if($val == 'hold'){
			return $result['initital'] - $result['intermediate'] - $result['final'];  
		}
		return $result[$val];
	}

	public function get_pct($val, $result)
	{
		if($val == 'hold'){
			return ($result['initital'] - $result['intermediate'] - $result['final']) / $result['initital'];  
		}
		return $result[$val]/$result['initital'];
	}

	public function update_bop()
	{
		$data = array(
			'penyusutan' => $this->input->post('penyusutan'),
			'listrik' => $this->input->post('listrik'),
			'lain_lain' => $this->input->post('lain_lain')
		);
		$status = $this->hm->update_id('id', $this->input->post('hpp_id'), $data);
		echo json_encode(array('status' => $status));
   }

   public function get_material_cost($id, $wo_id)
   {
		$result = $this->mc->get_material_list($id, $wo_id);
		$total = 0;
		foreach($result as $value){
			$unit_price = $value->price;
			$total += round(($value->pick-$value->return)*$unit_price, 2);
		}
		return $total;
   }

}
