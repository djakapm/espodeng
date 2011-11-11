<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UpdateModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
	    $this->load->database();
    }


    public function parse_data($string){
        $line_separator = "\n";
        $column_separator = '#';
        $csv_data = array();
        $rows = explode($line_separator,$string);
        array_pop($rows);//clean extra row
        foreach($rows as $row){
            $csv_datum = explode($column_separator,$row);
            $district = $this->guess_district($csv_datum[3]);
            if(empty($district)){
                $csv_datum[] = array();
            }
            else{
                $csv_datum[] = $district;
            }
            $csv_data[] = $csv_datum;

        }

        return $csv_data;
        
    }

    public function insert_data_to_incremented_table($selected_data,$unit_prices,$next_unit_prices
        ,$delivery_times,$destination_ids,$increment_table_name,$logistic_service_type,$logistic_company_id,$origin_id
        ,$ambigous_cities,$ambigous_districts){
        foreach($selected_data as $selected_datum){
            $insert = '';
            $index = intval($selected_datum);
            $unit_price = str_replace(',','',$unit_prices[$index]);
            $next_unit_price = str_replace(',','',$next_unit_prices[$index]);
            $delivery_time = $delivery_times[$index];
            $destination_id = $destination_ids[$index];
            $is_unknownn_district = $destination_id == -1;
            if($is_unknownn_district){
                $unknown_districts_data[] = $ambigous_cities[$index].', '.$ambigous_districts[$index];
                continue;
            }
            $insert = $this->create_insert($increment_table_name,$logistic_service_type,$logistic_company_id,$origin_id
                ,$destination_id,$unit_price,$next_unit_price,$delivery_time);
            $this->db->simple_query($insert);           
        }

        return $unknown_districts_data;
        
    }

    public function create_or_replace_incremented_table($incremented_table_name,$original_table_name){
        $this->db->query('drop table if exists '.$incremented_table_name,FALSE);
        $this->db->query('create table '.$incremented_table_name.' like '.$original_table_name,FALSE);        
    }

    public function create_incremented_table_name($table_name){
        $datestring = "%d%m%Y";
        $time = time();
        $incremented_table_name = $table_name.'_'.mdate($datestring, $time);;
        return $incremented_table_name;
    }

    private function create_insert($table_name,$service_type,$company,$origin,$destination,$unit_price,
        $next_unit_price,$delivery_time){
        $insert = "";
        $insert .= "insert into ".$table_name;
        $insert .= " (service_type_id,company_id,origin_id,destination_id,unit_price,next_unit_price,delivery_time)";
        $insert .= " values(".$service_type.",".$company.",".$origin.",".$destination.","
        .$unit_price.",".$next_unit_price.",".$delivery_time.")";

        return $insert;
    }


    public function guess_district($district_name){
        $select = 'ord.id,ord.name as district_name,orc.name as city_name';
        $from = 'ongkir_ref_district ord';
        $table_join = 'ongkir_ref_city orc';
        $table_join_clause = 'orc.id = ord.city_id';
        $table_join_type = 'inner';

        $this->db->select($select);
        $this->db->from($from);
        $this->db->join($table_join,$table_join_clause,$table_join_type);

        $query = $this->db->where(array('ord.name'=>$district_name));
        $query = $this->db->get();

        $rows = $query->result();
        if(empty($rows)){
            $this->db->select($select);
            $this->db->from($from);
            $this->db->join($table_join,$table_join_clause,$table_join_type);
            $query = $this->db->where('match(ord.name) against ("'.$this->db->escape_str($district_name).'")');
            $query = $this->db->get();
            $rows = $query->result();            
        }

    	$search_result = array();
    	foreach($rows as $row){
    		$district_name = $row->district_name;
    		$city_name = $row->city_name;
    		$search_result[$row->id] = $district_name.' ('.$row->id.'), '.$city_name;
    	}

    	return $search_result;

    }


}
?>