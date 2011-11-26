<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . '/controllers/test/Toast.php');


class Logistic_Service_Rank_Test_Case extends Toast {
	private $data = array();


	function __construct()
	{
	    parent::Toast(__FILE__);
	    $this->load->model('businessmodel','business');
	}
		

	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {}

	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->My_model->getError();
	 */
	function _post() {}


	/* TESTS BELOW */
	function test_calculate_weighted_average(){
		$data = array(
			'1'=>array('total_price'=>31000,'delivery_time'=>5,'logistic_rank'=>0),
			'2'=>array('total_price'=>40500,'delivery_time'=>2,'logistic_rank'=>0),
			'3'=>array('total_price'=>42000,'delivery_time'=>2,'logistic_rank'=>0)
		);

		$weighted_average = $this->business->weighted_average($data);		
		$this->_assert_equals(round($weighted_average),round(3666.66666666667));
	}

	function test_calculate_average(){
		$data = array(
			'1'=>array('total_price'=>31000,'delivery_time'=>5,'logistic_rank'=>0),
			'2'=>array('total_price'=>40500,'delivery_time'=>2,'logistic_rank'=>0),
			'3'=>array('total_price'=>42000,'delivery_time'=>2,'logistic_rank'=>0)
		);
		$weighted_average = 3666.66666666667;

		$average = $this->business->average($data,$weighted_average);		
		$this->_assert_equals(round($average),41500);
	}

	function test_remove_below_average_data(){
		$data = array(
			'1'=>array('total_price'=>31000,'delivery_time'=>5,'logistic_rank'=>0),
			'2'=>array('total_price'=>40500,'delivery_time'=>2,'logistic_rank'=>0),
			'3'=>array('total_price'=>42000,'delivery_time'=>2,'logistic_rank'=>0)
		);
		$average = 41500;
		$data = $this->business->below_average_pivot_filter($data,$average);		
		$this->_assert_equals(count($data),2);
	}


	function test_calculate_oke_score(){
		$data = array(
			'1'=>array('total_price'=>31000,'delivery_time'=>5,'logistic_rank'=>0),
			'2'=>array('total_price'=>40500,'delivery_time'=>2,'logistic_rank'=>0),
		);
		$data = $this->business->calculate_oke_score($data);
		$this->_assert_equals(round($data["1"]["total_oke_score"],2),round(1,2));
		$this->_assert_equals(round($data["1"]["delivery_time_oke_score"],2),round(2.5,2));
		$this->_assert_equals(round($data["2"]["total_oke_score"],2),round(1.1328671328671,2));
		$this->_assert_equals(round($data["2"]["delivery_time_oke_score"],2),round(1,2));
	}

	function test_calculate_weighted_oke_score(){
		$data = array( "1" => array( "total_price" => 31000, "delivery_time" => 5, "logistic_rank" => 0, "total_oke_score" => 1 ,
		"delivery_time_oke_score" => 2.5 ),

		"2" => array( "total_price" => 40500, "delivery_time" => 2, "logistic_rank" => 0, "total_oke_score" => 1.1328671328671, 
		"delivery_time_oke_score" => 1, )); 

		$data = $this->business->calculcate_weighted_oke_score($data,0.4,0.6);
		$this->_assert_true($data["1"]["total_weighted_oke_score"] == 0.4);
		$this->_assert_true($data["1"]["delivery_time_weighted_oke_score"] == 1.5);
		$this->_assert_true(round($data["2"]["total_weighted_oke_score"],2),round(0.45314685314685,2));
		$this->_assert_true(round($data["2"]["delivery_time_weighted_oke_score"],2),round(0.6,2));
	}

	function test_calculate_final_score(){
		$data = array( "1" => array( "total_price" => 31000, "delivery_time" => 5, "logistic_rank" => 0, "total_oke_score" => 1 ,
		"delivery_time_oke_score" => 2.5, "total_weighted_oke_score" => 0.4, "delivery_time_weighted_oke_score" => 1.5 ),
		"2" => array( "total_price" => 40500, "delivery_time" => 2, "logistic_rank" => 0, "total_oke_score" => 1.1328671328671, 
		"delivery_time_oke_score" => 1, "total_weighted_oke_score" => 0.45314685314685, "delivery_time_weighted_oke_score" => 0.6 
		)); 

		$data = $this->business->calculate_final_score($data);


		$data = $this->business->calculate_final_score($data);
		$this->_assert_equals(round($data["1"]["final_score"],2),round(0.95,2));
		$this->_assert_equals(round($data["2"]["final_score"],2),round(0.52657342657343,2));
	}

	function test_update_data_with_final_score(){
		$final_scores = array('1'=>array('final_score'=>0.95),'2'=>array('final_score'=>0.52657342657342));
		$data = array(
			'1'=>array('total_price'=>31000,'delivery_time'=>5,'logistic_rank'=>0),
			'2'=>array('total_price'=>40500,'delivery_time'=>2,'logistic_rank'=>0),
			'3'=>array('total_price'=>42000,'delivery_time'=>2,'logistic_rank'=>0)
		);

		$data = $this->business->update_data_with_final_score($data,$final_scores);
		$this->_assert_equals(round($data["1"]["final_score"],2),round(0.95,2));
		$this->_assert_equals(round($data["2"]["final_score"],2),round(0.52657342657343,2));
	}


	function test_get_logistic_rank(){

		$data = array(
			'1'=>array('total_price'=>31000,'delivery_time'=>5,'logistic_rank'=>0),
			'2'=>array('total_price'=>40500,'delivery_time'=>2,'logistic_rank'=>0),
			'3'=>array('total_price'=>42000,'delivery_time'=>2,'logistic_rank'=>0)
		);


		$weighted_average = $this->business->weighted_average($data);
		$average = $this->business->average($data,$weighted_average);

		$data = $this->business->below_average_pivot_filter($data,$average);

		$data = $this->business->calculate_oke_score($data);

		$data = $this->business->calculcate_weighted_oke_score($data,0.4,0.6);
		
		$data = $this->business->calculate_final_score($data);
	}



}
