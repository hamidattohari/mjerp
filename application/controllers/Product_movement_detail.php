<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_movement_detail extends MY_Controller {

	function  __construct() {
		parent::__construct();
		$this->load->helper('tablefield');
		$this->load->model('product_movement_model', 'pmm');
		$this->load->model('product_movement_det_model', 'pmdm');
		$this->load->model('product_process_model', 'ppm');
		$this->load->model('processes_model', 'prcm');
		$this->load->model('work_orders_model', 'wom');
		$this->load->model('work_order_detail_model', 'wodm');
		$this->load->model('products_model', 'pm');	
		$this->load->model('product_inventory_model', 'pim');	
		$this->load->model('unfinished_product_inventory_model', 'upim');	
		$this->load->model('machine_model', 'mm');	
		$this->load->model('time_table_model', 'ttm');	
	}

	private function get_column_attr(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('name', '', 'Name');  
		return $table->getColumns();
	}

	private function get_column_attr1(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('code', '', 'Code');
		$table->addColumn('qty', '', 'Qty');    
		$table->addColumn('length', '', 'Length');    
		$table->addColumn('unit', '', 'Unit');    
		$table->addColumn('created_by', '', 'Created by');     
		$table->addColumn('date', '', 'Date');     
		$table->addColumn('actions', '', 'Actions');     
		return $table->getColumns();
	}

	public function index($woid, $pid)
	{
		$pname = $this->pm->get_by_id('id', $pid);

		$data['title'] = "ERP | Product Movement Details";
		$data['page_title'] = "Product Movement Details ".$pname->name;
		$data['table_title'] = "List Processes";	
		$data['table_title1'] = "List Product Movement ";	
		$data['breadcumb']  = array("Production", "Product Movement");
		$data['page_view']  = "production/product_movement_detail";		
		$data['js_asset']   = "product-movement-detail";	
		$data['columns']    = $this->get_column_attr();		
		$data['columns1']    = $this->get_column_attr1();		
		$data['process'] = $this->prcm->get_data($pid);	
		$data['menu'] = $this->get_menu();					
		$data['woid'] = $woid;					
		$data['pid'] = $pid;				
		$data['csrf'] = $this->csrf;	
		$this->add_history($data['page_title']);	
		$this->load->view('layouts/master', $data);
	}

	public function details($woid, $pid)
	{
		$pname = $this->pm->get_by_id('id', $pid);
		$pm_id = $this->pmm->get_by_id('work_orders_id = '.$woid.' AND products_id = ', $pid);

		$data['title'] = "ERP | Product Movement Details";
		$data['page_title'] = "Product Movement Details <strong>".$pname->name."</strong>";
		$data['table_title'] = "List Processes";	
		$data['table_title1'] = "List Product Movement ";	
		$data['breadcumb']  = array("Production", "Product Movement");
		$data['page_view']  = "production/product_movement_detail";		
		$data['js_asset']   = "product-movement-detail";	
		$data['columns']    = $this->get_column_attr();		
		$data['columns1']    = $this->get_column_attr1();		
		$data['process'] = $this->prcm->get_data($pid);		
		$data['machine'] = $this->mm->get_all_data();	
		$data['menu'] = $this->get_menu();					
		$data['woid'] = $woid;					
		$data['pid'] = $pid;				
		$data['pm_id'] = $pm_id->id;				
		$data['csrf'] = $this->csrf;	
		$data['button'] = $this->prcm->get_data($pid);	
		$this->load->view('layouts/master', $data);
	}

	public function view_data($woid, $pid, $prid){
		$result = $this->pmdm->get_product_movement_detail1($woid, $pid, $prid);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['code'] = $value->code;
			$row['qty'] = $value->qty;
			$row['length'] = $value->length.'/'.$value->length1;
			$row['unit'] = "m";
			$row['created_by'] = $value->created_by;
			$row['date'] = $value->date;
			$row['actions'] = '<button class="btn btn-sm btn-info" onClick="edit('.$woid.','.$pid.','.$prid.','.$value->pm_id.','.$value->id.','.$value->length.','.$value->id.')" type="button"><i class="fa fa-edit"></i></button>
			<button class="btn btn-sm btn-info" onClick="movePrint('.$woid.','.$pid.','.$prid.','.$value->pm_id.','.$value->id.','.$value->qty.','.$value->length.')" type="button">Move</button>';
			$data[] = $row;
			$count++;
		}

		$result['data'] = $data;
		echo json_encode($result);
	}

	public function view_data1($woid, $pid, $prid){
		$result = $this->pmdm->get_product_movement_detail($woid, $pid, $prid);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['code'] = $value->code.sprintf('%04s', $value->no);
			$row['length'] = $value->length;
			$row['qty'] = $value->qty;
			$row['unit'] = "roll";
			$row['created_by'] = $value->created_by;
			$row['date'] = $value->date;
			$row['actions'] = '';
			$data[] = $row;
			$count++;
		}

		$result['data'] = $data;

		echo json_encode($result);
	}

	function generate_code($woid, $pid){
		$data = array(
			'work_orders_id' => $woid,
			'products_id' => $pid,
			'machine_id' => Null
		);

		$data = $this->add_adding_detail($data);
		$machine_code = $this->input->post('machine_id');
		$codes = $this->generate_product_code($woid, $pid);
		$temp = explode(" ", $codes);
		$temp[0] .= $machine_code;
		$result = $temp[0]." ".$temp[1];
		$inserted = $this->pmm->add_id($data);

		$data2 = array(
			'code' => $result,
			'from_id' => 0,
			'qty' => 1,
			'length' => $this->input->post('length'),
			'date' => date('Y-m-d H:i:s'),
			'product_movement_id' => $inserted,
			'processes_id' => 1,
			'machine_id' => $this->input->post('machine_id')
		);

		$insert = $this->pmdm->add($data2);

		echo json_encode(array('id' => $inserted));
	}

	function update($id){
		$data = array(
			'length' => $this->input->post('length'),
			'machine_id' => $this->input->post('machine_id')
		);
		$status = $this->pmdm->update_id('id', $id, $data);
		echo json_encode(array('id' => $status));
	}

	public function get_product_movement_detail($woid, $pid, $prid)
	{
		$result = $this->pmdm->get_product_movement_detail($woid, $pid, $prid);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$code = str_replace(" ", "", $value->code);
			$row['id'] = $value->id;
			$row['code'] = substr($code, 0, sizeof($code)-4);
			$row['created_by'] = $value->created_by;
			$row['actions'] = '<button class="btn btn-sm btn-info" onClick="edit('.$woid.','.$prid.')" type="button">Details</button>';
			$data[] = $row;
			$count++;
		}

		$result = $data;

		echo json_encode($result);
	}

	function get_by_id($id){
		$detail = $this->pmm->get_by_id('id', $id);
		echo json_encode($detail);
	}

	

	function delete($id){        
		$status = $this->pmm->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

	function jsgrid_functions($woid = -1, $pid = -1, $prid = -1){
		switch($_SERVER["REQUEST_METHOD"]) {
			case "GET":
			$result = $this->ttm->get_time_table($woid, $pid, $prid);
			$data = array();
			$count = 0;
			foreach($result as $value){
				$row = array();
				$row['id'] = $value->id;
				$row['date'] = $value->date;
				$row['time_start'] = $value->time_start;
				$row['time_end'] = $value->time_end;
				$row['note'] = $value->note;
				$data[] = $row;
				$count++;
			}

			$result = $data;
			echo json_encode($result);
			break;

			case "POST":
			// $data = array(
			// 	'wo_id' => $this->input->post('wo_id'),
			// 	'processes_id' => $this->input->post('processes_id'),
			// 	'products_id' => $this->input->post('products_id'),
			// 	'date' => $this->input->post('datetime'),
			// 	'time_start' => $this->input->post('time_start'),
			// 	'time_end' => $this->input->post('time_end'),
			// 	'note' => $this->input->post('note'),
			// );
			// $result = $this->ttm->add($data);

			$row = array();
			$row['id'] = $result;
			$row['date'] = $this->input->post('datetime');
			$row['time_start'] = $this->input->post('time_start');
			$row['time_end'] = $this->input->post('time_end');
			$row['note'] = $this->input->post('note');

			echo json_encode($row);
			break;

			case "PUT":
			// $this->input->raw_input_stream;
			// $data = array(
			// 	'date' => $this->input->input_stream('datetime'),
			// 	'time_start' => $this->input->input_stream('time_start'),
			// 	'time_end' => $this->input->input_stream('time_end'),
			// 	'note' => $this->input->input_stream('note'),
			// );
			// $result = $this->ttm->update('id',$this->input->input_stream('id'),$data);
			break;

			case "DELETE":
			$this->input->raw_input_stream;
			$status = $this->ttm->delete('id', $this->input->input_stream('id'));
			break;
		}
	}

	public function add_time()
	{
		$data = array(
			'wo_id' => $this->input->post('wo_id'),
			'processes_id' => $this->input->post('processes_id'),
			'products_id' => $this->input->post('products_id'),
			'date' => $this->input->post('datetime'),
			'time_start' => $this->input->post('time_start'),
			'time_end' => $this->input->post('time_end'),
			'note' => $this->input->post('note2'),
		);
		$inserted = $this->ttm->add($data);
		echo json_encode(array('status' => $inserted));
	}

	public function edit_time()
	{
		$data = array(
			'date' => $this->input->post('datetime'),
			'time_start' => $this->input->post('time_start'),
			'time_end' => $this->input->post('time_end'),
			'note' => $this->input->post('note2'),
		);
		$status = $this->ttm->update('id', $this->input->post('details_id'), $data);
		echo json_encode(array('status' => $status));
	}

	public function generate_product_code($woid, $pid)
	{
		$products = $this->pm->get_by_id('id', $pid);

		$month = $this->get_roman_number(date('n'));
		$cat = null;
		if ($products->product_categories_id == 1) {
			$cat = 'MC';
		} else {
			$cat = 'AFTP';
		}
		$num = preg_replace('/[^0-9,.]/', "", $products->code);
		$pm = $this->pmm->count_product_movement($woid, $pid)+1;
		$count_pm = sprintf("%04s",$pm);

		$code = $month.$cat.$num." ".$count_pm;
		return $code;
	}

	public function add_to_process()
	{
		$machine_code = $this->input->post('machine_id1');

		$pmd = $this->pmdm->get_by_id('id', $this->input->post('pmd_id'));
		$code = $pmd->code;
		$temp = explode(" ", $code);
		$temp[0] .= $machine_code;
		$result = $temp[0]." ".$temp[1];
		if ($this->pmdm->get_last_no($result)) {
			$last_no = $this->pmdm->get_last_no($result)->no;
		} else {
			$last_no = 0;
		}
		for ($i=0; $i < $this->input->post('no'); $i++) { 
			$data = array(
				'processes_id' => $this->input->post('process_id'),
				'code' => $result,
				'product_movement_id' => $this->input->post('pm_id'),
				'from_id' => $this->input->post('pmd_id'),
				'no' => $i+$last_no+1,
				'qty' => 1,
				'length' => $this->input->post('length1'),
				'note' => $this->input->post('note'),
				'machine_id' => $this->input->post('machine_id1'),
				'date' => date('Y-m-d H:i:s')
			);
			$this->pmdm->add($data);
		}
		$status = true;

		if ($this->input->post('process_id') == 0) {
			$product_movement = $this->pmm->get_by_id('id', $this->input->post('pm_id'));
			$data1 = array(
				'product_movement_id' => $this->input->post('pm_id'),
				//'qty' => $this->input->post('ke')-$this->input->post('dari')+1,
				'type' => 'in',
				'date' => date('Y-m-d H:i:s'),
				'products_id' => $product_movement->products_id
			);
			$this->pim->add($data1);
		} elseif ($this->input->post('process_id') == -1) {
			$product_movement = $this->pmm->get_by_id('id', $this->input->post('pm_id'));
			$data1 = array(
				'product_movement_id' => $this->input->post('pm_id'),
				//'qty' => $this->input->post('ke')-$this->input->post('dari')+1,
				'type' => 'in',
				'date' => date('Y-m-d H:i:s'),
				'products_id' => $product_movement->products_id
			);
			$this->upim->add($data1);
		}

		echo json_encode(array('id' => $status));
	}

	public function update_process()
	{
		$machine_code = $this->input->post('machine_id');
		$count = 0;
		foreach ($this->input->post('item') as $value) {
			$pmd = $this->pmdm->get_by_id('id', $value['id']);
			$code = $pmd->code;
			$temp = explode(" ", $code);
			$temp[0] .= $machine_code;
			$result = $temp[0]." ".$temp[1];
			$data = array(
				'processes_id' => $this->input->post('process_id'),
				'code' => $result,
				'note' => $this->input->post('note'),
				'date' => date('Y-m-d H:i:s')
			);
			$status = $this->pmdm->update('id', $value['id'], $data);
			$count++;
		}

		if ($this->input->post('process_id') == 0) {
			$product_movement = $this->pmm->get_by_id('id', $this->input->post('pm_id'));
			$data1 = array(
				'product_movement_id' => $this->input->post('pm_id'),
				'qty' => $count,
				'type' => 'in',
				'date' => date('Y-m-d H:i:s'),
				'products_id' => $product_movement->products_id
			);
			$this->pim->add($data1);
		} elseif ($this->input->post('process_id') == -1) {
			$product_movement = $this->pmm->get_by_id('id', $this->input->post('pm_id'));
			$data1 = array(
				'product_movement_id' => $this->input->post('pm_id'),
				'qty' => $count,
				'type' => 'in',
				'date' => date('Y-m-d H:i:s'),
				'products_id' => $product_movement->products_id
			);
			$this->upim->add($data1);
		}

		echo json_encode(array('id' => $status));
	}

}
