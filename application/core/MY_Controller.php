<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_controller extends CI_Controller {
	
	protected $csrf;

    function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('previllage_model', 'previllage');
		$this->load->model('history_model', 'history_model');
		//$this->lang->load('login', 'english');
        if(!$this->session->userdata('loggedIn')){
            redirect('login');
		}
		$this->csrf = array(
			'name' => $this->security->get_csrf_token_name(),
        	'hash' => $this->security->get_csrf_hash()
		);
	}

	function normalize_text($input){
        return ucwords(strtolower($input));
	}
	
	function to_mysql_date($date){
		return date("Y-m-d H:m:s", strtotime($date));
	}

	function toFormat($date, $format)
	{
		return date($format, strtotime($date));	
	}

	function mysql_time_now(){
		return date("Y-m-d H:m:s");
	}

	function formatCurrency($value)
	{
		return number_format($value, 2, ",", ".");	
	}

	function formatNumber($value)
	{
		return number_format($value, 2, ",", ".");	
	}


	function add_adding_detail($data){
		$data['created_at'] = $this->mysql_time_now();
		$data['created_by'] = $this->session->userdata('name');
		return $data;
	}

	function add_updating_detail($data){
		$data['updated_at'] = $this->mysql_time_now();
		$data['updated_by'] = $this->session->userdata('name');
		return $data;
	}

	function get_menu(){
		return $this->previllage->get_previllage();
	}

	function get_roman_number($number){
		$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
		$returnValue = '';
		while ($number > 0) {
			foreach ($map as $roman => $int) {
				if($number >= $int) {
					$number -= $int;
					$returnValue .= $roman;
					break;
				}
			}
		}
		return $returnValue;
	}

	public function upload_file($param){

		$data = array();

		$config['upload_path']   = './assets/'.$param['folder'].'/';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['max_size']      = '2000';
		$config['overwrite']     =  true;
		$config['file_name']     =  $param['file_name'];

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($param['field']))
		{
			$data['status'] = false;
			$data['msg'] = $this->upload->display_errors();
		}
		else
		{
			$upd = $this->upload->data();	
			$data['status'] = true;
			$data['msg'] = $param['folder'].'/'.$param['file_name'].$upd['file_ext'];
		}
		return $data;
	}

	public function add_history($page)
	{
		$d = array(
			'date' => date("Y-m-d h:m:s"),
			'name' => $this->session->userdata('name'),
			'page' => $page,
			'actions' => "visit"
		);
		$status = $this->history_model->add($d);
		$this->history_model->delete_old_history();	
	}

}
