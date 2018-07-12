<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->model('previllage_model', 'pm');
	}

	function set_initial_previllage(){
		$status = $this->pm->set_initial_previllage();
		if($status){
			echo "Successs!!";
		}else{
			echo "Failed";
		}
	}

}
