<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BasicDataModel extends CI_Model {

	var $fulltext_search_limit = 10;

    function __construct()
    {
        parent::__construct();
	    $this->load->database();
    }

    public function supported_origin($district_id){
    	$supported_origin_state_ids = array(4);
    	$is_supported = FALSE;
    	$sql = 
    	"select city.state_id
    		from ongkir_ref_district district 
			inner join ongkir_ref_city city on city.id = district.city_id
			inner join ongkir_ref_state state on state.id = city.state_id
			where district.id=$district_id";
    	$query = $this->db->query($sql);
    	foreach($query->result() as $row){
    		if(in_array($row->state_id,$supported_origin_state_ids)){
    			$is_supported = TRUE;
    			break;
    		}
    	}
    	
    	return $is_supported;
    }

    public function fulltext_search($text){
    	$sql = 
    	"select district.id district_id,district.name district_name,city.name city_name,state.name state_name 
    		from ongkir_ref_district district 
			inner join ongkir_ref_city city on city.id = district.city_id
			inner join ongkir_ref_state state on state.id = city.state_id
			where 
			match(district.name) against('$text')";
			$sql .= " limit 0, ".$this->fulltext_search_limit;

		$fulltext_search_result = array();
    	$query = $this->db->query($sql);
    	foreach($query->result() as $row){
    		$fulltext_search_result[$row->district_id] = array($row->district_name,$row->city_name,$row->state_name);
    	}

    	return $fulltext_search_result;
    }

	public function logistic_company(){
		$query = $this->db->query('select * from ongkir_ref_logistic_company');
		$logistic_companies = array();
		foreach ($query->result() as $row)
		{
			$logistic_companies[] = $row;
		}
		
		return $logistic_companies;
	}

	public function country(){
		$query = $this->db->query('select * from ongkir_ref_country');
		$countries = array();
		foreach ($query->result() as $row)
		{
			$countries[] = $row;
		}
		
		return $countries;
	}

	public function state($country_id){		
		if(empty($country_id)){
			$query = $this->db->query('select * from ongkir_ref_state');
			
		}
		else{
			$query = $this->db->query('select * from ongkir_ref_state where country_id='.$country_id);			
		}

		$states = array();
		foreach ($query->result() as $row)
		{
			$states[] = $row;
		}
		
		return $states;
	}
	
	public function city($state_id){
		if(empty($state_id)){
			$query = $this->db->query('select * from ongkir_ref_city');
		}
		else{
			$query = $this->db->query('select * from ongkir_ref_city where state_id='.$state_id);
		}

		$cities = array();
		foreach ($query->result() as $row)
		{
			$cities[] = $row;
		}
		
		return $cities;
	}

	public function district($city_id){
		if(empty($city_id)){
			$query = $this->db->query('select * from ongkir_ref_district');
		}
		else{
			$query = $this->db->query('select * from ongkir_ref_district where city_id='.$city_id);
		}

		$districts = array();
		foreach ($query->result() as $row)
		{
			$districts[] = $row;
		}
		
		return $districts;
	}

	public function load_district($district_id){
		$district_query = $this->db->get_where('ongkir_ref_district',array('id'=>$district_id));
		$district_results = $district_query->result();
		if(empty($district_results)){ return FALSE;}
		return $district_results[0];
			
	}
    

}
?>