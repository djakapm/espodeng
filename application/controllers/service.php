<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends CI_Controller {

	function __construct()
	{
	    parent::__construct();
	    $this->load->model('BasicDataModel','basicdata');
	    $this->load->model('JNEModel','jne');
	    $this->load->model('TIKIModel','tiki');
	    $this->load->model('BusinessModel','business');
	    $this->load->helper('inflector');
	    // $this->load->library('recaptcha');

	}

	public function index(){
		$results = array(
			array('name'=>'a','total_price'=>6000,'delivery_time'=>4),
			array('name'=>'b','total_price'=>7500,'delivery_time'=>3),
			array('name'=>'c','total_price'=>18000,'delivery_time'=>2),
			array('name'=>'d','total_price'=>25000,'delivery_time'=>1)		
		);
		
		print_r($this->business->logistic_rank($results));
	}

	public function validate(){
		// $data = array();
		// $data['site_name'] = 'palingoke.info';
		// $data['site_title'] = 'Ongkir Paling Oke';
		// $response = $this->input->post('recaptcha_response_field');

	    // if ($this->check_captcha($response)) 
	    // {

	    	$origin_id = $this->input->post('o');
	    	$destination_id = $this->input->post('d');
	    	$weight = $this->input->post('w');
	    	echo json_encode($this->compose_price($origin_id,$destination_id,$weight));
	    // }
	    // else
	    // {
	    // 	echo json_encode(array('status'=>401,'message'=>'Captcha input is invalid'));
	    // }
		
	}

	// function check_captcha($val) {
	//   $original_val = $this->input->post('recaptcha_challenge_field');
	//   if ($this->recaptcha->check_answer($this->input->ip_address(),$original_val,$val)) {
	//     return TRUE;
	//   } else {
	//     return FALSE;
	//   }
	// }

	public function origin_location(){
		// $json_response = array(3728=>'Tanah Abang,Jakarta Pusat,Indonesia',4551=>'Mojokerto, Jawa Tengah, Indonesia');
		if(empty($_GET)){
			echo json_encode(array());
			return;
		}

		// $text = $_GET['q'];
		$text = $_GET['search'];
		if(empty($text)){
			echo json_encode(array());
		}
		else{
			$result = $this->basicdata->search_origin_location($this->basicdata->get_registry_as_string('ongkir_logistic_service')
			,$text);
			$json_response = array();
			foreach($result as $key => $value){
				$json_response[] = array('id'=>$key,'text'=>$value[0]);
			}
			echo json_encode($json_response);
		}
	}


	public function location(){
		// $json_response = array(3728=>'Tanah Abang,Jakarta Pusat,Indonesia',4551=>'Mojokerto, Jawa Tengah, Indonesia');
		if(empty($_GET)){
			echo json_encode(array());
			return;
		}

		// $text = $_GET['q'];
		$text = $_GET['search'];
		if(empty($text)){
			echo json_encode(array());
		}
		else{
			$result = $this->basicdata->search_location($text);
			$json_response = array();
			foreach($result as $key => $value){
				$json_response[] = array('id'=>$key,'text'=>$value[0]);
			}
			echo json_encode($json_response);
		}
	}

	public function price(){

		$origin_id = $_GET['o'];
		$destination_id = $_GET['d'];
		$weight = $_GET['w'];
		echo json_encode($this->compose_price($origin_id,$destination_id,$weight));
	}

	private function compose_price($origin_id='',$destination_id='',$weight=1){
		$results = array();
		$origin = $this->basicdata->load_location($origin_id);
		$destination = $this->basicdata->load_location($destination_id);


		$is_supported = $this->basicdata->supported_origin($origin_id);
		if($is_supported){

			//Currrently only service 'Jakarta to ...'
			// $origin_id = 2272; //Jakarta Pusat district
			if($weight < 1){$weight = 1;}
			$source_table = $this->basicdata->current_logistic_service_table();
			//JNE
			$logistic_services = $this->jne->get_logistic_service($origin_id,$destination_id,$weight,$source_table);
			if($logistic_services === FALSE){
				$jne_result = array('status'=>404,'message'=>'Not Found');			
			}
			else{
				$jne_result = array('status'=>200);

				foreach($logistic_services as $logistic_service){
					$service_name = $logistic_service['service_name'];
					$delivery_time = $logistic_service['delivery_time'];
					$unit_price = $logistic_service['unit_price'];
					$total_price = $logistic_service['total_price'];
					$jne_result['data'][] = array(
					'service_name'=>humanize($service_name),
					'name'=>'jne',
					'unit_price'=>$unit_price,
					'total_price'=>$total_price,
					'delivery_time'=>$delivery_time);					
				}
			}

			//TIKI
			$logistic_services = $this->tiki->get_logistic_service($origin_id,$destination_id,$weight,$source_table);
			if($logistic_services === FALSE){
				$tiki_result = array('status'=>404,'message'=>'Not Found');			
			}
			else{
				$tiki_result = array('status'=>200);
				foreach($logistic_services as $logistic_service){
					$service_name = $logistic_service['service_name'];
					$delivery_time = $logistic_service['delivery_time'];
					$unit_price = $logistic_service['unit_price'];
					$total_price = $logistic_service['total_price'];
					$tiki_result['data'][] = array(
					'service_name'=>humanize($service_name),
					'name'=>'tiki',
					'unit_price'=>$unit_price,
					'total_price'=>$total_price,
					'delivery_time'=>$delivery_time);
				}
			}

			//Merge jne and tiki result
			if($jne_result['status'] == 200){
				$results = array_merge($results,$jne_result['data']);
				
			}
			if($tiki_result['status'] == 200){
				$results = array_merge($results,$tiki_result['data']);
				
			}

			//Then do the ranking for 'paling ok'
			$results = $this->business->logistic_rank($results);

		$json_response = array(
			'status'=>200,'message'=>'OK','origin'=>'Jakarta',
			'destination'=>$destination->name,
			'results'=>$results
			);			
		}
		else{
			$json_response = array('status'=>400,'message'=>'Origin not supported');			
		}

		return $json_response;
	}


}
?>