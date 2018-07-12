<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('purchase_report_model', 'prm');
			$this->load->model('receiving_report_model', 'rrm');
			$this->load->model('purchase_return_report_model', 'prrm');
			$this->load->model('sales_report_model', 'srm');
			$this->load->model('shipping_report_model', 'sprm');
			$this->load->model('sales_return_report_model', 'srrm');
	}

    function _remap($param) {
		$temp = explode("_", $param);
		if(method_exists($this, $param)){
			return call_user_func_array(array($this, $param), array());
		}else{
			$this->index($param);
		}
    }

    function index($param){
        if($param == "purchase"){
			$this->get_purchase_report_view();
		}else if($param == "receiving"){
			$this->get_receiving_report_view();
		}else if($param == "purchase_return"){
			$this->get_purchase_return_report_view();
		}else if($param == "sales"){
			$this->get_sales_report_view();
		}else if($param == "shipping"){
			$this->get_shipping_report_view();
		}else if($param == "sales_return"){
			$this->get_sales_return_report_view();
		}
	}
	
	/* Generate Purchase Report View */

	private function get_purchase_report_column(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
		$table->addColumn('date', '', 'Date');
		$table->addColumn('code', '', 'Code');        
		$table->addColumn('vat', '', 'VAT');        
		$table->addColumn('vendor', '', 'Vendor');      
		$table->addColumn('name', '', 'Item');      
		$table->addColumn('price', '', 'Total Price');      
        return $table->getColumns();
	}
	
	public function get_purchase_report_view()
	{
		$data['title'] = "ERP | Purchase Report";
		$data['page_title'] = "Purchase Report";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Report", "Purchase Report");
		$data['page_view']  = "report/purchase_report";		
		$data['js_asset']   = "purchase_report";	
		$data['columns']    = $this->get_purchase_report_column();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);						
		$this->load->view('layouts/master', $data);
	}

	public function view_purchase_report(){
		$result = $this->prm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$count++;
			$row['id'] = $count;
			$row['date'] = $this->toFormat($value->date, "Y-m-d");
			$row['code'] = $value->code;
			$vat = "PPn";
			if($value->vat == 0){
				$vat = "Non PPn";
			}
			$row['vat'] = $vat;
			$row['name'] = $value->name;
			$row['vendor'] = $value->vendor;
			$row['price'] = $value->price;
			$data[] = $row;
		}

		$result['data'] = $data;
		echo json_encode($result);
	}

	/* Generate Receiving Report View */

	private function get_receiving_report_column(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
		$table->addColumn('date', '', 'Date');
		$table->addColumn('code', '', 'Code');        
		$table->addColumn('vendor', '', 'Vendor');      
		$table->addColumn('name', '', 'Item');      
		$table->addColumn('qty', '', 'Qty');      
		$table->addColumn('pcode', '', 'Purchase Code');      
        return $table->getColumns();
	}
	
	public function get_receiving_report_view()
	{
		$data['title'] = "ERP | Receiving Report";
		$data['page_title'] = "Receiving Report";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Report", "Receiving Report");
		$data['page_view']  = "report/receiving_report";		
		$data['js_asset']   = "receiving_report";	
		$data['columns']    = $this->get_receiving_report_column();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);						
		$this->load->view('layouts/master', $data);
	}

	public function view_receiving_report(){
		$result = $this->rrm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$count++;
			$row['id'] = $count;
			$row['date'] = $this->toFormat($value->date, "Y-m-d");
			$row['code'] = $value->code;
			$row['vendor'] = $value->vendor;
			$row['name'] = $value->name;
			$row['qty'] = $value->qty;
			$row['pcode'] = $value->pcode;
			$data[] = $row;
		}

		$result['data'] = $data;
		echo json_encode($result);
	}

	/* Generate Purchase Return Report View */

	private function get_purchase_return_report_column(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
		$table->addColumn('date', '', 'Date');
		$table->addColumn('code', '', 'Code');        
		$table->addColumn('rcode', '', 'Receiving Code');      
        return $table->getColumns();
	}
	
	public function get_purchase_return_report_view()
	{
		$data['title'] = "ERP | Purchase Return Report";
		$data['page_title'] = "Purchase Return Report";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Report", "Purchase Return Report");
		$data['page_view']  = "report/purchase_return_report";		
		$data['js_asset']   = "purchase_return_report";	
		$data['columns']    = $this->get_purchase_return_report_column();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);						
		$this->load->view('layouts/master', $data);
	}

	public function view_purchase_return_report(){
		$result = $this->prrm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$count++;
			$row['id'] = $count;
			$row['date'] = $this->toFormat($value->date, "Y-m-d");
			$row['code'] = $value->code;
			$row['rcode'] = $value->rcode;
			$data[] = $row;
		}

		$result['data'] = $data;
		echo json_encode($result);
	}

	/* Generate Sales Report View */

	private function get_sales_report_column(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('date', '', 'Date');
		$table->addColumn('code', '', 'Code');        
		$table->addColumn('vat', '', 'VAT');        
		$table->addColumn('description', '', 'Note');        
		$table->addColumn('customer', '', 'Customer');        
		return $table->getColumns();
	}
	
	public function get_sales_report_view()
	{
		$data['title'] = "ERP | Sales Report";
		$data['page_title'] = "Sales Report";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Report", "Sales Report");
		$data['page_view']  = "report/sales_report";		
		$data['js_asset']   = "sales_report";	
		$data['columns']    = $this->get_sales_report_column();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);						
		$this->load->view('layouts/master', $data);
	}

	public function view_sales_report(){
		$result = $this->srm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$count++;
			$row['id'] = $count;
			$row['date'] = $this->toFormat($value->date, "Y-m-d");
			$row['code'] = $value->code;
			$vat = "PPn";
			if($value->vat == 0){
				$vat = "Non PPn";
			}
			$row['vat'] = $vat;
			$row['description'] = $value->description;
			$row['customer'] = $value->customer;
			$data[] = $row;
		}

		$result['data'] = $data;
		echo json_encode($result);
	}

	/* Generate Shipping Report View */

	private function get_shipping_report_column(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('date', '', 'Date');
		$table->addColumn('code', '', 'Code');        
		$table->addColumn('transport', '', 'Transport');        
		$table->addColumn('note', '', 'Note');        
		$table->addColumn('prcode', '', 'Sales Code');        
		return $table->getColumns();
	}
	
	public function get_shipping_report_view()
	{
		$data['title'] = "ERP | Shipping Report";
		$data['page_title'] = "Shipping Report";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Report", "Shipping Report");
		$data['page_view']  = "report/shipping_report";		
		$data['js_asset']   = "shipping_report";	
		$data['columns']    = $this->get_shipping_report_column();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);						
		$this->load->view('layouts/master', $data);
	}

	public function view_shipping_report(){
		$result = $this->sprm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$count++;
			$row['id'] = $count;
			$row['date'] = $this->toFormat($value->date, "Y-m-d");
			$row['code'] = $value->code;
			$row['transport'] = $value->transport;
			$row['note'] = $value->note;
			$row['prcode'] = $value->prcode;
			$data[] = $row;
		}

		$result['data'] = $data;
		echo json_encode($result);
	}

	/* Generate Sales Return Report View */

	private function get_sales_return_report_column(){
		$table = new TableField();
		$table->addColumn('id', '', 'ID');
		$table->addColumn('date', '', 'Date');
		$table->addColumn('code', '', 'Code');       
		$table->addColumn('note', '', 'Note');        
		$table->addColumn('pscode', '', 'Shipping Code');        
		return $table->getColumns();
	}
	
	public function get_sales_return_report_view()
	{
		$data['title'] = "ERP | Sales Return Report";
		$data['page_title'] = "Sales Return Report";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Report", "Sales Return Report");
		$data['page_view']  = "report/sales_return_report";		
		$data['js_asset']   = "sales_return_report";	
		$data['columns']    = $this->get_sales_return_report_column();	
		$data['csrf'] = $this->csrf;		
		$data['menu'] = $this->get_menu();			
		$this->add_history($data['page_title']);						
		$this->load->view('layouts/master', $data);
	}

	public function view_sales_return_report(){
		$result = $this->srrm->get_output_data();
		$data = array();
		$count = 0;
		foreach($result['data'] as $value){
			$row = array();
			$count++;
			$row['id'] = $count;
			$row['date'] = $this->toFormat($value->date, "Y-m-d");
			$row['code'] = $value->code;
			$row['note'] = $value->note;
			$row['pscode'] = $value->pscode;
			$data[] = $row;
		}

		$result['data'] = $data;
		echo json_encode($result);
	}

}
