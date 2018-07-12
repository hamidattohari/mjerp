<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	function  __construct() {
		parent::__construct();
			$this->load->helper('tablefield');
			$this->load->model('users_model', 'um');
			$this->load->model('roles_model', 'rm');
	}
	
	private function get_column_attr(){
        $table = new TableField();
        $table->addColumn('id', '', 'ID');
		$table->addColumn('name', '', 'Name');
        $table->addColumn('username', '', 'Username');        
        $table->addColumn('email', '', 'Email');        
        $table->addColumn('address', '', 'Address');        
        $table->addColumn('telp', '', 'Telp');        
        $table->addColumn('actions', '', 'Actions');        
        return $table->getColumns();
    }
	
	public function index()
	{
		$data['title'] = "ERP | Users";
		$data['page_title'] = "Users";
		$data['table_title'] = "List Item";		
		$data['breadcumb']  = array("Settings", "Users");
		$data['page_view']  = "settings/users";
		$data['js_asset']   = "users";
		$data['columns']    = $this->get_column_attr();	
		$data['csrf'] = $this->csrf;	
		$data['menu'] = $this->get_menu();			
		$data['roles'] = $this->rm->get_all_data();		
		$this->add_history($data['page_title']);
		$this->load->view('layouts/master', $data);
	}

	public function view_data(){
		$result = $this->um->get_output_data();
        $data = array();
        $count = 0;
        foreach($result['data'] as $value){
            $row = array();
            $row['id'] = $value->id;
			$row['name'] = $value->name;
			$row['username'] = $value->username;
			$row['email'] = $value->email;
			$row['address'] = $value->address;
			$row['telp'] = $value->telp;
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
			'name' => $this->normalize_text($this->input->post('name')),
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),		
			'email' => $this->input->post('email'),
			'address' => $this->normalize_text($this->input->post('address')),
			'telp' => $this->input->post('telp'),
			'roles_id' => $this->input->post('roles_id'),
			'created_at' => date("Y-m-d H:m:s")
		);
		$inserted = $this->um->add($data);
		echo json_encode(array('status' => $inserted));
	}

	function get_by_id($id){
		$detail = $this->um->get_by_id('id', $id);
		echo json_encode($detail);
	}

	function update(){
		$data = array(
			'name' => $this->normalize_text($this->input->post('name')),
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'address' => $this->normalize_text($this->input->post('address')),
			'telp' => $this->input->post('telp'),
			'roles_id' => $this->input->post('roles_id'),
			'updated_at' => date("Y-m-d H:m:s")
		);
		if($this->input->post('password') != $this->um->get_substr_password($this->input->post('change_id'))){
			$data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
		}
		$status = $this->um->update('id', $this->input->post('change_id'), $data);
		echo json_encode(array('status' => $status));
   }

	function delete($id){        
		$status = $this->um->delete('id', $id);
		echo json_encode(array('status' => $status));
	}

}
