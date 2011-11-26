<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BusinessModel extends CI_Model {
	private $min_total_price = 0;
	private $min_delivery_time = 0;
	private $avg_total_price = 0;
	private $avg_delivery_time = 0;

	private $price_weight = 0.4;
	private $delivery_time_weight = 0.6;

	public function logistic_rank($logistic_data){
		$weighted_average = $this->business->weighted_average($logistic_data);
		$average = $this->business->average($logistic_data,$weighted_average);

		$logistic_data = $this->business->below_average_pivot_filter($logistic_data,$average);

		$logistic_data = $this->business->calculate_oke_score($logistic_data);

		$logistic_data = $this->business->calculcate_weighted_oke_score($logistic_data,
			$this->price_weight,$this->delivery_time_weight);
		
		$logistic_data = $this->business->calculate_final_score($logistic_data);
		
		return $logistic_data;	
	}




	public function update_data_with_final_score($data,$final_scores){
		$new_data = array();
		foreach($data as $key=>$value){
			$new_value = $value;
			$new_value['final_score'] = (array_key_exists($key,$final_scores) ? $final_scores[$key]['final_score'] : 1);	
			$new_data[$key] = $new_value;
		}

		return $new_data;
	}

	public function below_average_pivot_filter($data,$average){
		$results = array();
		foreach($data as $key=>$value){
			if($value['total_price'] <= $average){
				$results[$key] = $value;
			}
		}

		return $results;
	}

	public function calculate_final_score($data){
		$new_data = array();
		foreach($data as $key=>$value){
			$new_value = $value;
			$result = ($value['total_weighted_oke_score']+$value['delivery_time_weighted_oke_score'])/2;
			$new_value['final_score'] = $result;
			$new_data[$key] = $new_value;
		}

		return $new_data;
	}

	public function calculcate_weighted_oke_score($data,$total_weight,$delivery_time_weight){
		$new_data = array();

		foreach($data as $key=>$value){
			$new_value = $value;
			$new_value['total_weighted_oke_score'] = $value['total_oke_score'] * $total_weight;
			$new_value['delivery_time_weighted_oke_score'] = $value['delivery_time_oke_score'] * $delivery_time_weight;			
			$new_data[$key] = $new_value;

		}

		return $new_data;
	}

	public function calculate_oke_score($data){
		$new_data = array();
		$totals = $this->extract($data,'total_price');

		$delivery_times = $this->extract($data,'delivery_time');
		$count = count($totals);
		$total_avg = array_sum($totals)/$count;
		$delivery_time_avg = array_sum($delivery_times)/$count;
		$counter = 0;
		foreach($data as $key=>$value){
			$new_value = $value;
			$total_dividers = $this->extract(array_slice($data,$counter),'total_price');
			$total_dividers[] = $total_avg;
			$delivery_time_dividers = $this->extract(array_slice($data,$counter),'delivery_time');
			
			$new_value['total_oke_score']	= $value['total_price']/min($total_dividers);
			$new_value['delivery_time_oke_score']	= $value['delivery_time']/min($delivery_time_dividers);
			$new_data[$key] = $new_value;
			$counter++;
		}	

		return $new_data;
	}


	public function average($data,$weighted_average){
		$result = 0;
		$totals = $this->extract($data,'total_price');
		$sum = array_sum($totals);
		$count = count($totals);
		$result = $sum/$count + min(10000,$weighted_average);
		return $result;
	}

	public function weighted_average($data){
		$weighted_average = 0;
		$totals = $this->extract($data,'total_price');

		$max = max($totals);
		$min = min($totals);
		$count = count($totals);
		$weighted_average = ($max-$min)/$count;			
		return $weighted_average;
	}

	private function extract($data,$property){
		$results = array();
		foreach($data as $key=>$value){
			$results[] = $value[$property];
		}

		return $results;
	}
}
?>
