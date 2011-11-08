<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	private $data = array();

	function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->data['site_name'] = 'palingoke.info';
		$this->data['site_title'] = 'Ongkir';
	}

	public function login(){
		$this->load->view('admin/login_page',$this->data);
	}

	public function validate(){
		$login_id = $this->input->post('login-input');
		$password = $this->input->post('password-input');
		if($login_id == 'espodeng' && $password == 'espodeng6000'){
			redirect('/admin/landing');
		}
		else{
			redirect('/admin/unauthorize','refresh');
		}
	}

	public function landing(){
		$this->load->view('admin/landing_page',$this->data);
	}

	public function unauthorize(){
		echo 'Fuck You!';
	}

	public function upload_data(){
		$this->load->view('admin/upload_data_page',$this->data);
	}

	public function process_uploaded_data(){
		$config['upload_path'] = './upload/';
		$config['allowed_types'] = 'csv';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('upload-input'))
		{
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
		}
		else
		{
			$csv_data = array();
			$upload_data = $this->upload->data();
			print_r($upload_data);
			$string = read_file($upload_data['full_path']);
			$rows = explode(',',$string);
			foreach($rows as $row){
				$csv_datum = explode('#',$row);
				$csv_data[] = $csv_datum;
			}
			$this->data['current_file'] = $upload_data['orig_name'];
			$this->data['csv_data'] = $csv_data;
			$this->load->view('admin/upload_data_page',$this->data);
		}		
	}

}

?>
