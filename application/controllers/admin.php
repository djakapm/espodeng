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
                $this->load->model('uploadmodel','uploaddata');
		$this->data['site_name'] = 'palingoke.info';
		$this->data['site_title'] = 'Ongkir';
                $this->load->library('pagination');
	}


	public function setting(){
		$this->data['settings'] = $this->basicdata->get_registry();
		$this->load->view('admin/setting_page',$this->data);
	}

	public function save_setting(){
		$registry_names = $this->input->post('registry_name');
		$numeric_values = $this->input->post('numeric_value');
		$string_values = $this->input->post('string_value');
		$this->basicdata->save_registry($registry_names,$numeric_values,$string_values);

		redirect('admin/setting');
	}

	public function rebuild_reference_data_landing(){
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
			$this->update->insert_reference_data($string,$this->basicdata->get_registry_as_string('column_separator'));
			
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
			redirect('admin/landing');
		}
		else{
			redirect('admin/unauthorize');
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
		echo '<h1>Fuck You! and Fuck Off!</h1>';
		echo '<h1>or</h1>';
		echo "<button style='height:50px;width:30%' onclick=\"location.href='".site_url('admin/login')."'\">Try again</button>";
	}

	public function upload_data(){
		if($this->validate_session()){
                    
                    $this->data['origin_districts'] = $this->get_origin_districts();
                    $this->data['logistic_companies'] = $this->get_logistic_companies();
                    $this->data['logistic_service_types'] = $this->get_logistic_service_types();
                    
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

		$this->update->insert_data_to_origin_table($origin_id);
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
                    
                    $createNewTable = $this->input->post('create_new_table');
                    $createNewTable = empty($createNewTable)?false:true;
                    $tableName = $this->basicdata->current_logistic_service_table();
                    if ($createNewTable) {
                        $newTableName = $this->update->create_incremented_table_name('ongkir_logistic_service');
                        $this->update->create_or_replace_incremented_table($newTableName);
                        $tableName = $newTableName;
                    }
                    
                    // save upload info 
                    $this->uploaddata->insert_temp_upload_csv_info(
                            $this->input->post('origin_district'),
                            $this->input->post('logistic_company'),
                            $this->input->post('logistic_service_type'),
                            $tableName
                    );
                    
                    $this->update->insert_data_to_origin_table($this->input->post('origin_district'));
                    
                    // this will insert the data into temp table
                    $product = $this->uploaddata->parse_and_insert_to_temp($upload_data['full_path']);
                    
                    
                    // this will insert match data to location_service table
                    $this->uploaddata->insert_match_data_to_incremented_table($tableName);

                    // delete match data
                    $this->uploaddata->delete_match_data();
                    
                    // this will insert match data to database
//                    $this->data['all_district_count'] = $product['district_count'];
//                    $this->data['ambigous_district_count'] = $product['ambigous_district_count'];
//                    $this->load->view('admin/upload_data_preview_page');
                    redirect('admin/preview_uploaded_data');
		}		
	}
        
        public function preview_uploaded_data() {
            $this->data = array_merge($this->data, $this->uploaddata->get_all());
            $this->load->view('admin/upload_data_preview_page',$this->data);
        }
        
        public function update_selected_data() {
            
            $uploadInfo = $this->uploaddata->get_upload_info();
            
            if (!empty($uploadInfo)) {
                $arr = $this->input->post('selection');

                if (!empty($arr) && is_array($arr)) {
                    foreach ($arr as $key => $value) {
                        if ($value != "-1" && $value != "") {
                            $locInfo = explode("=", $value,2);
                            $this->uploaddata->select_location($key, $locInfo[0], $locInfo[1], 
                                    $uploadInfo['logistic_table_name'], 
                                    $uploadInfo['logistic_service_type'],
                                    $uploadInfo['logistic_company'],
                                    $uploadInfo['origin_id']);
                        }
                    }
                }
                
            }
            
            
            $this->data= array_merge($this->data, $this->uploaddata->get_all());
            $this->load->view('admin/upload_data_preview_page',$this->data);
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
                $rows = $this->basicdata->get_all_origin();
		foreach($rows as $row){
			$origin_districts[$row->id] = strtoupper($row->name);
		}

		return $origin_districts;

	}

}

?>
