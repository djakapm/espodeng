<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	private $data = array();

	function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->load->helper('form');
		$this->load->model('basicdatamodel','basicdata');
		$this->load->model('updatemodel','update');
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

	public function process_data(){
		$origin_id = 2434;
		$logistic_company_id = $this->input->post('logistic_company');
		$logistic_service_type = $this->input->post('logistic_service_type');
		$selected_data = $this->input->post('selected_data');
		$unit_prices = $this->input->post('unit_price');
		$next_unit_prices = $this->input->post('next_unit_price');
		$delivery_times = $this->input->post('delivery_time');
		$destination_ids = $this->input->post('guessed_district');
		foreach($selected_data as $selected_datum){
			$index = intval($selected_datum);
			$unit_price = $unit_prices[$index];
			$next_unit_price = $next_unit_prices[$index];
			$delivery_time = $delivery_times[$index];
			$destination_id = $destination_ids[$index];
			echo 'update ongkir_logistic_service set unit_price='.$unit_price.',next_unit_price='.$next_unit_price.',delivery_time='.$delivery_time.' where company_id='.$logistic_company_id.' and service_type_id='.$logistic_service_type.' and origin_id='.$origin_id.' and destination_id='.$destination_id.'<br/>';
		}
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
			$line_separator = "\n";
			$column_separator = '#';
			$csv_data = array();
			$upload_data = $this->upload->data();
			// print_r($upload_data);
			$string = read_file($upload_data['full_path']);
			$rows = explode($line_separator,$string);
			array_pop($rows);//clean extra row
			foreach($rows as $row){
				$csv_datum = explode($column_separator,$row);
				$district = $this->update->guess_district($csv_datum[0]);
				if(empty($district)){
					$csv_datum[] = '';
				}
				else{
					$csv_datum[] = $district;
				}
				$csv_data[] = $csv_datum;

			}
			$this->data['logistic_companies'] = $this->get_logistic_companies();
			$this->data['logistic_service_types'] = $this->get_logistic_service_types();
			$this->data['current_file'] = $upload_data['orig_name'];
			$this->data['csv_data'] = $csv_data;
			$this->load->view('admin/upload_data_page',$this->data);
		}		
	}

	private function get_logistic_service_types(){
		$logistic_service_types = array();
		$rows = $this->basicdata->logistic_service_type();
		foreach($rows as $row){
			$logisticservice_types[$row->id] = strtoupper($row->company_name.' - '.$row->service_type_name);
		}

		return $logisticservice_types ;		
	}

	private function get_logistic_companies(){
		$logistic_companies = array();
		$rows = $this->basicdata->logistic_company();
		foreach($rows as $row){
			$logistic_companies[$row->id] = strtoupper($row->name);
		}

		return $logistic_companies;

	}

}

?>
