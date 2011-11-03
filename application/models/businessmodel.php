<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BusinessModel extends CI_Model {
		private $min_total_price = 0;
		private $min_delivery_time = 0;
		private $avg_total_price = 0;
		private $avg_delivery_time = 0;

	public function logistic_rank($logistic_data){
		if(empty($logistic_data)){return;}
		$average = $this->average($logistic_data,'total_price');
		$below_average = array();
		$above_average = array();

		foreach($logistic_data as $logistic_datum){
			if($logistic_datum['total_price'] <= $average){
				$below_average[] = $logistic_datum;
			}
			else{
				$logistic_datum['rank'] = 1;
				$above_average[] = $logistic_datum;
			}
		}
		$this->avg_total_price = $this->average($below_average,'total_price');
		$this->avg_delivery_time = $this->average($below_average,'delivery_time');

		$counter = 0;
		$results = array();
		foreach($below_average as $item){
			$this->min_total_price = $this->minimum(array_slice($below_average,$counter),'total_price',$this->avg_total_price);
			$this->min_delivery_time = $this->minimum(array_slice($below_average,$counter),'delivery_time',$this->avg_delivery_time);
			$item['rank'] = $this->rank_weight($item['total_price'],$item['delivery_time']);
			$results[] = $item;
			$counter++;
		}
		$results = array_merge($results,$above_average);
		return $results;

	}

	private function rank_weight($total_price,$delivery_time){
		$total_price_weight = 0.7;
		$delivery_time_weight = 0.3;
		$rank = 0;
		$total_price_oke_score = $total_price/$this->min_total_price;
		$total_price_weight_oke_score = $total_price_oke_score*$total_price_weight;

		$delivery_time_oke_score = round($delivery_time/$this->min_delivery_time,1);

		$delivery_time_weight_oke_score = round($delivery_time_oke_score*$delivery_time_weight,1);

		$rank = round(($total_price_weight_oke_score+$delivery_time_weight_oke_score)/2,1);
		return round($rank,2);
	}

	private function minimum($below_average,$key,$default_value){
		$min = PHP_INT_MAX;
		foreach($below_average as $item){
			$value = $item[$key];
			if($value < $min){
				$min = $value;
			}
		}

		return $min;
	}

	private function average($logistic_data,$key){
		$sum = 0;
		foreach($logistic_data as $logistic_datum){
			$sum += $logistic_datum[$key];
		}
		$avg = $sum/count($logistic_data);
		return $avg;
	}
}
?>
