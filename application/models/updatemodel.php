<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UpdateModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
	    $this->load->database();
    }

    public function guess_district($district_name){
    	$query = $this->db->get_where('ongkir_ref_district','match(name) against (\''.$district_name.'\')');
    	$search_result = array();
    	foreach($query->result() as $row){
    		$district_name = $row->name;
    		$city_id = $row->city_id;
    		$search_result[$row->id] = $district_name.' ('.$row->id.')';
    	}

    	return $search_result;

    }


}
?>