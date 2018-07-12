<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends MY_Controller {

	function  __construct() {
		parent::__construct();
			//$this->load->model('Login', 'login');
		$this->load->model('purchase_model', 'prc');
		$this->load->model('purchase_det_model', 'prd');
		$this->load->model('receive_model', 'rcv');
		$this->load->model('receive_det_model', 'rcvd');
		$this->load->model('materials_model', 'mm');
		$this->load->model('vendors_model', 'vm');
		$this->load->model('customers_model', 'cm');
		$this->load->model('shipping_model', 'sm');
		$this->load->model('shipping_details_model', 'sdm');
		$this->load->model('projects_model', 'prm');
		$this->load->model('work_orders_model', 'wom');		
		$this->load->model('work_order_detail_model', 'wodm');		
		$this->load->model('project_details_model', 'pdm');
		$this->load->model('product_movement_det_model', 'pmdm');
		$this->load->model('material_usage_model', 'mu');
		$this->load->model('nonmaterial_usage_model', 'nmu');
		$this->load->model('material_usage_cat_model', 'muc');
		$this->load->model('material_usage_det_model', 'mud');
		$this->load->model('material_return_model', 'mr');
		$this->load->model('material_return_det_model', 'mrd');
		$this->load->model('machine_model', 'mm');
		$this->load->model('hpp_model', 'hm');
		$this->load->model('products_model', 'pm');
		$this->load->model('btkl_model', 'bm');
		$this->load->model('application_model', 'am');
	}
                
	public function index()
	{
		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_sales_order($id)
	{
		$data['so'] = $this->prm->get_by_id('id', $id);
		$data['so_detail'] = $this->pdm->get_project_details($id);
		$data['date'] = $this->toFormat($data['so']->created_at, "d F Y");

		$customer_id = $data['so']->customers_id;
		$data['customer'] = $this->cm->get_by_id('id', $customer_id);

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_sales";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_purchasing($id)
	{
		$data['purchasing'] = $this->prc->get_by_id('id', $id);
		$data['purchase_detail'] = $this->prd->get_purchase_details($id);
		$data['date'] = $this->toFormat($data['purchasing']->created_at, "d F Y");
		$data['logo'] = $this->am->get_logo();

		$vendor_id = $data['purchasing']->vendors_id;
		$data['vendor'] = $this->vm->get_by_id('id', $vendor_id);

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print3";
		$data['page_view']  = "invoice/invoice_purchasing";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_receiving($id)
	{
		$data['receiving'] = $this->rcv->get_receiving($id);
		$data['receive_det'] = $this->rcvd->get_receive_det_where_id1($id);
		$data['date'] = $this->toFormat($data['receiving']->receive_date, "d F Y");

		$vendor_id = $data['receiving']->vendors_id;
		$data['vendor'] = $this->vm->get_by_id('id', $vendor_id);

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_receiving";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_shipping($id)
	{
		$data['shipping'] = $this->sm->get_shipping_by_id($id);
		$data['shipping_det'] = $this->sdm->get_shipping_details($id);
		$data['date'] = $this->toFormat($data['shipping']->shipping_date, "d F Y");
		$data['logo'] = $this->am->get_logo();

		$customer_id = $data['shipping']->customers_id;
		$data['customer'] = $this->cm->get_by_id('id', $customer_id);

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print2";
		$data['page_view']  = "invoice/invoice_shipping";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_wo($id)
	{
		$data['work_orders'] = $this->wom->get_by_id('id',$id);
		$data['project'] = $this->prm->get_by_id('id',$data['work_orders']->projects_id);
		$data['work_order_detail'] = $this->wodm->get_work_order_details($id);
		$data['so'] = $this->prm->get_by_id('id', $data['work_orders']->projects_id);
		$data['date'] = $this->toFormat($data['work_orders']->created_at, "d F Y");
		$data['enddate'] = $this->toFormat($data['work_orders']->end_date, "d F Y");
		
		$customer_id = $data['so']->customers_id;
		$data['customer'] = $this->cm->get_by_id('id', $customer_id);
		
		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_wo";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_production($date, $prid)
	{
		$data['product_movement_detail'] = $this->pmdm->get_product_movement_detail_print($date, $prid);
		if ($prid == 0) {
			$data['process'] = "JADI";
		} else {
			$data['process'] = "SETENGAH JADI";
		}
		$data['date'] = $date;

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_production";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_pickup($id)
	{
		$data['material_usage'] = $this->mu->get_material_usage($id);
		$data['material_usage_detail'] = $this->mud->get_material_usage_details($id);

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_pickup";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_return($id)
	{
		$data['material_return'] = $this->mu->get_material_return($id);
		$data['material_return_detail'] = $this->mud->get_material_return_details($id);
		$data['date'] = $this->toFormat($data['material_return']->updated_at, "d F Y");

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_return";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_pickupnm($id)
	{
		$data['material_usage'] = $this->nmu->get_material_usage($id);
		$data['material_usage_detail'] = $this->nmud->get_material_usage_details($id);

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_pickup";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_returnnm($id)
	{
		$data['material_return'] = $this->nmu->get_material_return($id);
		$data['material_return_detail'] = $this->nmud->get_material_return_details($id);

		$data['title'] = "ERP | Invoice";
		$data['page_title'] = "Invoice";
		$data['breadcumb']  = array("Invoice");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_return";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function print_hpp($id)
	{
		$data['hpp'] = $this->hm->get_by_id('id', $id);
		$data['hpp_material'] = $this->format_hpp_material($id);
		$data['hpp_btkl'] = $this->format_hpp_btkl($id);
		$data['hpp_product_result'] = $this->format_product_result($id);

		$product_id = $data['hpp']->products_id;
		$data['product'] = $this->pm->get_by_id('id', $product_id);

		$data['title'] = "ERP | HPP";
		$data['page_title'] = "HPP";
		$data['breadcumb']  = array("HPP");
		$data['css_asset']   = "print";
		$data['page_view']  = "invoice/invoice_hpp";		
		$data['js_asset']   = "invoice";	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();							
		$this->load->view('layouts/master', $data);
	}

	public function format_hpp_material($id)
	{
		$result = $this->hm->get_material_list($id);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['category'] = $value->category;
			$row['name'] = $value->name;
			$row['pick'] = $value->pick;
			$row['used'] = $value->pick-$value->return;
			$row['return'] = $value->return;
			$row['symbol'] = $value->symbol;
			$unit_price = round($this->hm->get_per_pieces_price($value->id), 2);
			$row['unit_price'] = $unit_price;
			$row['total_price'] = round(($value->pick-$value->return)*$unit_price, 2);
			$data[] = $row;
			$count++;
		}

		$result = $data;
		return $result;
	}

	public  function format_hpp_btkl($id)
	{
		$result = $this->bm->get_btkl_list($id);
		$data = array();
		$count = 0;
		foreach($result as $value){
			$row = array();
			$row['id'] = $value->id;
			$row['processes_id'] = $value->processes_id;
			$row['qty'] = $value->qty;
			$row['price'] = $value->price;
			$row['total_price'] = $value->qty*$value->price;
			$data[] = $row;
			$count++;
		}

		$result = $data;
		return $result;
	}

	public  function format_product_result($id)
	{
		$status = array('initital', 'intermediate', 'final', 'waste');
		$details = $this->hm->get_by_id('id', $id);
		$data = array();
		
		if(isset($details->products_id)){
			$wos = $this->hm->get_all_wos($details->month, $details->year, $details->products_id);
			$product_result = $this->hm->get_product_result($wos, $details->products_id);
			foreach($status as $value){
				$row = array();
				$row['description'] = ucfirst($value);
				$row['qty'] = $this->get_qty($value, $product_result);
				$row['unit'] = "roll";
				$row['pct'] = ($this->get_pct($value, $product_result) * 100);
				$data[] = $row;
			}
		}

		$result = $data;
		return $result;
	}

	public function get_qty($val, $result)
	{
		if($val == 'waste'){
			return $result['initital'] - $result['intermediate'] - $result['final'];  
		}
		return $result[$val];
	}

	public function get_pct($val, $result)
	{
		if($val == 'waste'){
			return ($result['initital'] - $result['intermediate'] - $result['final']) / $result['initital'];  
		}
		return $result[$val]/$result['initital'];
	}

}
