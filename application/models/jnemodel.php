<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JNEModel extends CI_Model {
	function __construct()
    {
        parent::__construct();
	    $this->load->database();
    }

	public function get_logistic_service($origin_id,$destination_id,$weight){
		$product = array();
		$company_id = 1;

		$this->db->select('orst.name service_name,ols.delivery_time,ols.unit_price');
		$this->db->from('ongkir_logistic_service ols');
		$this->db->join('ongkir_ref_service_type orst', 'orst.company_id = ols.company_id and orst.id = ols.service_type_id');
		$this->db->where(array('ols.company_id'=>$company_id,'ols.origin_id'=>$origin_id,
		'ols.destination_id'=>$destination_id));
		$logistic_service_query = $this->db->get();  

		$logistic_service_results = $logistic_service_query->result();

		if(empty($logistic_service_results)){
			return FALSE;
		}
		else{
			foreach($logistic_service_results as $target){
				$sub_product = array();
				$sub_product['service_name'] = $target->service_name;			
				$sub_product['delivery_time'] = $target->delivery_time;
				$sub_product['unit_price'] = $target->unit_price;
				$sub_product['total_price'] = $this->calculate_total_price($weight,$target->unit_price);
				$product[] = $sub_product;
				
			}
		}

		return $product;
	}    	

	private function calculate_total_price($weight,$unit_price){
		return  $this->weight_rounding_rules($weight) * $unit_price;		
	}

	private function weight_rounding_rules($weight){
		$rounded_weight = floor($weight);
		$fraction = $weight - $rounded_weight;
		if($fraction > 0.2){
			$weight = $rounded_weight + 1;
		}

		return $weight;
	}
}
?>