<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	private $data = array();

	function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->load->helper('date');
		$this->load->helper('form');
		$this->load->model('basicdatamodel','basicdata');
		$this->load->model('updatemodel','update');
		$this->data['site_name'] = 'palingoke.info';
		$this->data['site_title'] = 'Ongkir';
	}

	public function rebuild_reference_data_landing(){
		// $this->data['last_rebuilt_date'] = $this->update->get_location_last_rebuilt_date();
		$this->load->view('admin/rebuild_reference_data_page',$this->data);
	}

	public function rebuild_reference_data(){
		$config['upload_path'] = './upload/';
		$config['allowed_types'] = 'csv|txt';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('upload-input'))
		{
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
		}
		else
		{
	        $upload_data = $this->upload->data();
	        $string = read_file($upload_data['full_path']);

			$this->update->empty_reference_table();
			$this->update->insert_reference_data($string);
			
			$this->load->view('admin/rebuild_reference_data_page',$this->data);
		}			
	}

	public function rebuild_location_landing(){
		$this->data['last_rebuilt_date'] = $this->update->get_location_last_rebuilt_date();
		$this->load->view('admin/rebuild_location_page',$this->data);
	}

	public function rebuild_location(){
		$this->update->empty_location_table();
		$this->update->insert_district_data();
		$this->update->insert_city_data();
		$this->data['last_rebuilt_date'] = $this->update->get_location_last_rebuilt_date();
		$this->load->view('admin/rebuild_location_page',$this->data);
	}

	private function validate_session(){
		$session_id = $this->session->userdata('logged_in');
		return $session_id;
	}

	public function login(){
		$this->session->sess_destroy();
		$this->load->view('admin/login_page',$this->data);
	}

	public function validate(){
		$login_id = $this->input->post('login-input');
		$password = $this->input->post('password-input');
		$logged_in = $login_id == 'espodeng' && $password == 'espodeng6000';
		if($logged_in){
			$this->session->set_userdata('logged_in', $logged_in);
			redirect('/admin/landing');
		}
		else{
			redirect('/admin/unauthorize','refresh');
		}
	}

	public function landing(){
		if($this->validate_session()){
			$this->load->view('admin/landing_page',$this->data);
		}
		else{
			$this->load->view('admin/login_page',$this->data);
		}
	}

	public function unauthorize(){
		echo '<h1>Fuck You!</h1>';
	}

	public function upload_data(){
		if($this->validate_session()){
			$this->load->view('admin/upload_data_page',$this->data);
		}
		else{
			$this->load->view('admin/login_page',$this->data);
		}
	}

	public function process_data(){
		$replace_data = $this->input->post('replace_data');
		$unknown_districts_data = array();
		$ambigous_cities = $this->input->post('ambigous_city');
		$ambigous_districts = $this->input->post('ambigous_district');
		$origin_id = $this->input->post('origin_district');
		$logistic_company_id = $this->input->post('logistic_company');
		$logistic_service_type = $this->input->post('logistic_service_type');
		$selected_data = $this->input->post('selected_data');
		$unit_prices = $this->input->post('unit_price');
		$next_unit_prices = $this->input->post('next_unit_price');
		$delivery_times = $this->input->post('delivery_time');
		$destination_ids = $this->input->post('guessed_district');
		$original_table_name = 'ongkir_logistic_service';

		$increment_table_name = $this->update->create_incremented_table_name($original_table_name);

		if(!empty($replace_data)){
			$this->update->create_or_replace_incremented_table($increment_table_name,$original_table_name);
			$table_info =  'Created table '.$increment_table_name;			
		}
		else{
			$table_info = 'Appending data to '.$increment_table_name.' table';			
		}

		$unknown_districts_data = $this->update->insert_data_to_incremented_table(
			$selected_data,$unit_prices,$next_unit_prices
			,$delivery_times,$destination_ids,$increment_table_name,$logistic_service_type,$logistic_company_id,$origin_id
			,$ambigous_cities,$ambigous_districts);

		
		$this->data['table_info'] = $table_info;
		$this->data['unknown_districts_data'] = $unknown_districts_data;
		$this->load->view('admin/post_processing_page',$this->data);
	}


	public function process_uploaded_data(){
		$config['upload_path'] = './upload/';
		$config['allowed_types'] = 'csv|txt';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('upload-input'))
		{
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
		}
		else
		{
	        $upload_data = $this->upload->data();
			$product = $this->update->parse_file($upload_data['full_path']);
			$csv_data = $product['csv_data'];
			$all_district_count = $product['district_count'];
			$ambigous_district_count = $product['ambigous_district_count'];;
			$unguessed_district_count = $product['unguessed_district_count'];

			$this->data['origin_districts'] = $this->get_origin_districts();
			$this->data['logistic_companies'] = $this->get_logistic_companies();
			$this->data['logistic_service_types'] = $this->get_logistic_service_types();
			$this->data['current_file'] = $upload_data['orig_name'];
			$this->data['csv_data'] = $csv_data;
			$this->data['all_district_count'] = $all_district_count;
			$this->data['ambigous_district_count'] = $ambigous_district_count;
			$this->data['unguessed_district_count'] = $unguessed_district_count;
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

	private function get_origin_districts(){
		$origin_districts = array();
		$rows = $this->basicdata->district();
		foreach($rows as $row){
			$origin_districts[$row->id] = strtoupper($row->name);
		}

		return $origin_districts;

	}

}

?>
