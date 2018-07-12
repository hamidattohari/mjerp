<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo_data extends MY_Controller {

	var $min = 1;
	var $max = 20;
	var $processes = array("Printing", "Slithering", "Perforation", "Lamination", "Rewind");
	var $material_categories = array("Tinta Asli", "Tinta Tap", "Kardus", "Core", "Solvent", "Pemanis", "Penolong");
	var $product_categories = array("Tipping", "Foil");
	var $usage_categories = array("Tinta/Solvent", "Kardus", "Core", "Pemanis", "Penolong");
	var $colors = array("Red", "Green", "Blue", "White", "Gold");
	var $uom = array("Kilogram", "Pieces", "Meter");
	var $uom_symbol = array("Kg", "Pc", "M");
	var $currency = array("Rupiah", "Dollar");
	var $currency_symbol = array("Rp", "$");
	var $currency_rate = array("1", "13500");
	var $tipping_proc = array(1,2,3,5);
	var $foil_proc = array(1,3,5);

	function  __construct() {
		parent::__construct();
			$this->load->model('material_cat_model', 'mcm');
			$this->load->model('materials_model', 'mm');
			$this->load->model('product_cat_model', 'pcm');
			$this->load->model('products_model', 'pm');
			$this->load->model('product_materials_model', 'pmm');
			$this->load->model('product_process_model', 'ppm');
			$this->load->model('usage_cat_model', 'ucm');
			$this->load->model('material_usage_cat_model', 'mucm');
			$this->load->model('processes_model', 'prm');
			$this->load->model('customers_model', 'cum');
			$this->load->model('vendors_model', 'vem');
			$this->load->model('colors_model', 'cm');
			$this->load->model('uom_model', 'um');
			$this->load->model('currency_model', 'curm');
	}

	function fill_demo_data(){
		$this->fill_process();
		$this->fill_customer();
		$this->fill_vendor();
		$this->fill_colors();
		$this->fill_uom();
		$this->fill_currency();
		$this->fill_material_category();
		$this->fill_material();
		$this->fill_product_category();
		$this->fill_product();
		$this->fill_product_material();
		$this->fill_product_process();
		$this->fill_usage_categories();
		//$this->fill_material_usage_categories();
	}

	//Fill Material Categories Data
	function fill_material_category(){
		$data = array();
		$i = 0;
		foreach($this->material_categories as $cat){
			$i++;
			$row = array();
			$row['id'] = $i;
			$row['name'] = $cat;
			$row['parent_id'] = 0;
			$row['created_at'] = $this->mysql_time_now();
			$data[] = $row;
 		}
		$this->mcm->add_batch($data);
	}

	//Fill Vendors Data
	function fill_vendor(){
		$data = array();
		for($i=$this->min; $i <= $this->max; $i++){
			$row = array();
			$row['id'] = $i;
			$row['code'] = "";
			$row['name'] =  "Vendor ".$i;
			$row['description'] =  "Vendor ".$i." Description";
			$row['address'] =  "Vendor ".$i." Address";
			$row['telp'] =  str_repeat("".$i, 12);
			$row['created_at'] = $this->mysql_time_now();
			$data[] = $row;
		}
		$this->vem->add_batch($data);
	}

	//Fill Material Data
	function fill_material(){
		$data = array();
		for($i=$this->min; $i <= $this->max; $i++){
			$rand = rand(1, sizeof($this->material_categories));
			$row = array();
			$row['id'] = $i;
			$row['name'] = $this->material_categories[$rand-1]." ".$i;
			$row['created_at'] = $this->mysql_time_now();
			$row['vendors_id'] = rand($this->min, $this->max);
			$row['uom_id'] = rand($this->min, sizeof($this->uom));
			$row['material_categories_id'] = $rand;
			$data[] = $row;
		}
		$this->mm->add_batch($data);
	}

	//Fill Processes Data
	function fill_process(){
		$data = array();
		$i = 0;
		foreach($this->processes as $proc){
			$i++;
			$row = array();
			$row['id'] = $i;
			$row['name'] = $proc;
			$row['created_at'] = $this->mysql_time_now();
			$data[] = $row;
		}
		$this->prm->add_batch($data);
	}

	//Fill Customers Data
	function fill_customer(){
		$data = array();
		for($i=$this->min; $i <= $this->max; $i++){
			$row = array();
			$row['id'] = $i;
			$row['code'] = "";
			$row['name'] =  "Customer ".$i;
			$row['description'] =  "Customer ".$i." Description";
			$row['address'] =  "Customer ".$i." Address";
			$row['telp'] =  str_repeat("".$i, 12);
			$row['created_at'] = $this->mysql_time_now();
			$data[] = $row;
		}
		$this->cum->add_batch($data);
	}


	//Fill Product Categories Data
	function fill_product_category(){
		$data = array();
		$i = 0;
		foreach($this->product_categories as $cat){
			$i++;
			$row = array();
			$row['id'] = $i;
			$row['name'] = $cat;
			$row['slug'] = strtolower($cat);
			$row['parent_id'] = 0;
			$row['created_at'] = $this->mysql_time_now();
			$data[] = $row;
		}
		$this->pcm->add_batch($data);
	}

	//Fill Product Data
	function fill_product(){
		$data = array();
		$tipping_count = 1;
		$foil_count = 1;
		for($i=$this->min; $i <= $this->max; $i++){
			$rand = rand(1, sizeof($this->product_categories));
			$row = array();
			$row['id'] = $i;
			$row['name'] = $this->product_categories[$rand-1]." ".$i;
			$row['created_at'] = $this->mysql_time_now();
			$row['product_categories_id'] = $rand;
			$row['uom_id'] = rand($this->min, sizeof($this->uom));
			// $row['code'] = sprintf('%03s', $tipping_count)."MC";
			// 	$tipping_count++;
			$cod = null;
			$col = null;
			if ($rand == 1) {
				$cod = sprintf('%03s', $tipping_count)."MC";
				$tipping_count++;
			} else {
				$rand_num = rand(1, sizeof($this->colors));
				$rand_color = $this->colors[$rand_num-1];
				$temp = substr($rand_color, 0, 2);
				$cod = strtoupper($temp).sprintf('%03s', $foil_count)."MC";
				$col = $rand_num;
				$foil_count++;
			}
			$row['code'] = $cod;
			$row['colours_id'] = $col;
			$data[] = $row;
		}
		//echo json_encode($data, JSON_PRETTY_PRINT);
		$this->pm->add_batch($data);
	}

	//Fill Product's Material Data
	function fill_product_material(){
		$data = array();
		$products = $this->pm->get_all_data();
		$i = 0;
		foreach($products as $product){
			$data = array();
			$n_material = rand(3, 5);
			$ids = array();
			for($n = 0; $n < $n_material; $n++){
				$id_material = rand($this->min, $this->max);
				while(in_array($id_material, $ids)){
					$id_material = rand($this->min, $this->max);
				}
				array_push($ids, $id_material);
			}
			foreach($ids as $id){
				$i++;
				$row = array();
				$row['id'] = $i;
				$row['products_id'] = $product->id;			
				$row['materials_id'] = $id;	
				$row['qty'] = rand(10, 100);		
				$data[] = $row;
			}
			$this->pmm->add_batch($data);
		}
	}

	//Fill Product's Process Data
	function fill_product_process(){
		$data = array();
		$products = $this->pm->get_all_data();
		$i = 0;
		foreach($products as $product){
			$data = array();
			if($product->product_categories_id == 1){
				foreach($this->tipping_proc as $pid){
					$i++;
					$row = array();
					$row['id'] = $i;
					$row['products_id'] = $product->id;
					$row['processes_id'] = $pid;
					$data[] = $row;
				}
				$this->ppm->add_batch($data);
			}else{	
				foreach($this->foil_proc as $pid){
					$i++;
					$row = array();
					$row['id'] = $i;
					$row['products_id'] = $product->id;
					$row['processes_id'] = $pid;
					$data[] = $row;
				}
				$this->ppm->add_batch($data);
			}
		}
	}

	//Fill Usage Categories
	function fill_usage_categories(){
		$data = array();
		$i = 0;
		foreach($this->usage_categories as $cat){
			$i++;
			$row = array();
			$row['id'] = $i;
			$row['name'] = $cat;
			$row['created_at'] = $this->mysql_time_now();
			$data[] = $row;
 		}
		$this->ucm->add_batch($data);
	}

	//Fill Colors
	function fill_colors(){
		$data = array();
		$i = 0;
		foreach($this->colors as $clr){
			$i++;
			$row = array();
			$row['id'] = $i;
			$row['code'] = substr($clr, 0, 2);
			$row['name'] = $clr;
			$data[] = $row;
 		}
		$this->cm->add_batch($data);
	}

	//Fill Uom
	function fill_uom(){
		$data = array();
		$i = 0;
		foreach($this->uom as $u){
			$i++;
			$row = array();
			$row['id'] = $i;
			$row['name'] = $u;
			$row['symbol'] = $this->uom_symbol[$i-1];
			$data[] = $row;
 		}
		$this->um->add_batch($data);
	}

	//Fill Uom
	function fill_currency(){
		$data = array();
		$i = 0;
		foreach($this->currency as $curr){
			$i++;
			$row = array();
			$row['id'] = $i;
			$row['name'] = $curr;
			$row['symbol'] = $this->currency_symbol[$i-1];
			$row['rate'] = $this->currency_rate[$i-1];
			$data[] = $row;
 		}
		$this->curm->add_batch($data);
	}

	//Fill Material Usage Categories
	function fill_material_usage_categories(){
		$data = array();
		$n = 0;
		$usages = $this->ucm->get_all_data();
		foreach($usages as $usage){
			$temp = explode("/", $usage->name);
			$ids = array();
			foreach($temp as $t){
				for($i = 0; $i = sizeof($this->material_categories)-1; $i++){
					if(strpos($this->material_categories[$i], $t) != false){
						array_push($ids, $i+1);
					}
				}
			}
			foreach($ids as $id){
				$n++;
				$row = array();
				$row['id'] = $n;
				$row['material_categories_id'] = $id;
				$row['usage_categories_id'] = $usage->id;
				$data[] = $row;
			}
			$this->mucm->add_batch($data);
 		}
	}

}
