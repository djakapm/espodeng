<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends CI_Controller {

	function __construct()
	{
	    parent::__construct();
	    $this->load->model('BasicDataModel','basicdata');
	    $this->load->model('JNEModel','jne');
	    $this->load->model('TIKIModel','tiki');
	    $this->load->model('ValidationModel','validation');
	    $this->load->helper('inflector');
	}

	public function _logistic_company(){
		$logistic_companies =$this->basicdata->logistic_company();
		echo json_encode($logistic_companies);
	}

	public function _country(){
		$countries = $this->basicdata->country();
		echo json_encode($countries);
	}

	public function _state(){
		$country_id = $_GET['c'];
		$states = $this->basicdata->state($country_id);
		echo json_encode($states);
	}
	
	public function _city(){
		$state_id = $_GET['s'];
		$cities = $this->basicdata->city($state_id);
		echo json_encode($cities);
	}

	public function _district(){
		$city_id = $_GET['c'];
		$districts = $this->basicdata->district($city_id);
		echo json_encode($districts);
	}

	public function supportedorigin(){
		if(empty($_GET)){
			echo json_encode(array());
			return;
		}

		$text = $_GET['q'];
		if(empty($text)){
			echo json_encode(array());
		}
		else{
			$json_response = $this->basicdata->supported_origin($text);
			echo json_encode($json_response);
		}		
	}

	public function place(){
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
				$json_response[] = array('id'=>$key,'text'=>humanize($value[0]).'('.humanize($value[1]).','.humanize($value[2]).')');
			}
			echo json_encode($json_response);
		}
	}

	public function price(){
		// $recaptcha_answer = $_GET['r'];
		// if(!$this->security->check_captcha($recaptcha_answer)){
		// 	$json_response = array('status'=>401,'message'=>'Unauthorized');
		// 	echo json_encode($json_response);	
		// 	return;
		// }

		$origin_id = $_GET['o'];
		$destination_id = $_GET['d'];
		$weight = $_GET['w'];
		$origin = $this->basicdata->load_district($origin_id);
		$destination = $this->basicdata->load_district($destination_id);


		$is_supported = $this->basicdata->supported_origin($origin_id);
		if($is_supported){
			//Currrently only service 'Jakarta to ...'

			$origin_id = 2434; //Jakarta Pusat district
			if($weight < 1){$weight = 1;}

			//JNE
			$logistic_services = $this->jne->get_logistic_service($origin_id,$destination_id,$weight);
			if($logistic_services === FALSE){
				$jne_result = array('status'=>404,'message'=>'Not Found');			
			}
			else{
				$jne_result = array();

				foreach($logistic_services as $logistic_service){
					$service_name = $logistic_service['service_name'];
					$delivery_time = $logistic_service['delivery_time'];
					$unit_price = $logistic_service['unit_price'];
					$total_price = $logistic_service['total_price'];
					$jne_result[] = array(
					'service_name'=>humanize($service_name),
					'name'=>'jne',
					'unit_price'=>$unit_price,
					'total_price'=>$total_price,
					'delivery_time'=>$delivery_time);					
				}
			}

			//TIKI
			$logistic_services = $this->tiki->get_logistic_service($origin_id,$destination_id,$weight);
			if($logistic_services === FALSE){
				$tiki_result = array('status'=>404,'message'=>'Not Found');			
			}
			else{
				$tiki_result = array();
				foreach($logistic_services as $logistic_service){
					$service_name = $logistic_service['service_name'];
					$delivery_time = $logistic_service['delivery_time'];
					$unit_price = $logistic_service['unit_price'];
					$total_price = $logistic_service['total_price'];
					$tiki_result[] = array(
					'service_name'=>humanize($service_name),
					'name'=>'tiki',
					'unit_price'=>$unit_price,
					'total_price'=>$total_price,
					'delivery_time'=>$delivery_time);
				}
			}

		$json_response = array(
			'status'=>200,'message'=>'OK','origin'=>'Jakarta','destination'=>$destination->name,
			'jne'=>$jne_result,
			'tiki'=>$tiki_result
			);			
		}
		else{
			$json_response = array('status'=>400,'message'=>'Origin not supported');			
		}

		echo json_encode($json_response);			
	}


}
?>