<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BasicDataModel extends CI_Model {

	var $search_limit = 10;

    function __construct()
    {
        parent::__construct();
	    $this->load->database();
    }

    public function supported_origin($district_id){
    	$supported_origin_state_ids = array(4);
    	$is_supported = FALSE;
    	$this->db->select('city.state_id');
    	$this->db->from('ongkir_ref_district district');
    	$this->db->join('ongkir_ref_city city','city.id = district.city_id','inner');
    	$this->db->join('ongkir_ref_state state','state.id = city.state_id','inner');
    	$this->db->where(array('district.id'=>$district_id));
    	$query = $this->db->get();
    	foreach($query->result() as $row){
    		if(in_array($row->state_id,$supported_origin_state_ids)){
    			$is_supported = TRUE;
    			break;
    		}
    	}
    	
    	return $is_supported;
    }

    public function search_location($text){
    	$this->db->select('id,district_name,city_name,state_name');
    	$this->db->from('view_ongkir_location');
    	$this->db->like('district_name',$text);
    	$this->db->or_like('city_name',$text);
    	$this->db->or_like('state_name',$text);
    	$this->db->limit($this->search_limit);


		$search_result = array();
    	$query = $this->db->get();
    	foreach($query->result() as $row){
    		$district_name = $row->district_name;
    		$city_name = $row->city_name;
    		$state_name = $row->state_name;
    		$search_result[$row->id] = array($district_name.' ('.$city_name.','.$state_name.')');
    	}

    	return $search_result;
    }

	public function logistic_service_type(){

		$this->db->select('orst.id as id,orlc.name as company_name ,orst.name as service_type_name');
		$this->db->from('ongkir_ref_service_type orst');
		$this->db->join('ongkir_ref_logistic_company orlc','orlc.id = orst.company_id','inner');
		$query = $this->db->get();
		$logistic_service_types = array();
		foreach ($query->result() as $row)
		{
			$logistic_service_types[] = $row;
		}
		
		return $logistic_service_types;
	}

	public function logistic_company(){
		$query = $this->db->get('ongkir_ref_logistic_company');
		$logistic_companies = array();
		foreach ($query->result() as $row)
		{
			$logistic_companies[] = $row;
		}
		
		return $logistic_companies;
	}

	public function country(){
		$query = $this->db->get('ongkir_ref_country');
		$countries = array();
		foreach ($query->result() as $row)
		{
			$countries[] = $row;
		}
		
		return $countries;
	}

	public function state($country_id){		
		if(empty($country_id)){
			$query = $this->db->get('ongkir_ref_state');
			
		}
		else{
			$query = $this->db->get_where('ongkir_ref_state',array('country_id'=>$country_id));			
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
			$query = $this->db->get('ongkir_ref_city');
		}
		else{
			$query = $this->db->get_where('ongkir_ref_city',array('state_id'=>$state_id));
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
			$query = $this->db->get('ongkir_ref_district');
		}
		else{
			$query = $this->db->get_where('ongkir_ref_district',array('city_id'=>$city_id));
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