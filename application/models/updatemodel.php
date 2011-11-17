<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UpdateModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
	    $this->load->database();
        date_default_timezone_set('Asia/Jakarta');
    }

    private function insert_country($country){
        $query = $this->db->get_where('ongkir_ref_country',array('name'=>$country));
        $rows = $query->result();
        if(!empty($rows)){
            return $rows[0]->id;
        }

        $this->db->insert('ongkir_ref_country',array('name'=>trim($country)));
        return $this->db->insert_id();
    }

    private function insert_state($state,$country_id){
        $query = $this->db->get_where('ongkir_ref_state',array('name'=>$state,'country_id'=>$country_id));
        $rows = $query->result();
        if(!empty($rows)){
            return $rows[0]->id;
        }
        $this->db->insert('ongkir_ref_state',array('name'=>trim($state),'country_id'=>$country_id));
        return $this->db->insert_id();
    }

    private function insert_city($city,$state_id){
        $query = $this->db->get_where('ongkir_ref_city',array('name'=>$city,'state_id'=>$state_id));
        $rows = $query->result();
        if(!empty($rows)){
            return $rows[0]->id;
        }

        $this->db->insert('ongkir_ref_city',array('name'=>trim($city),'state_id'=>$state_id));
        return $this->db->insert_id();
    }

    private function insert_district($district,$city_id){
        $query = $this->db->get_where('ongkir_ref_district',array('name'=>$district,'city_id'=>$city_id));
        $rows = $query->result();
        if(!empty($rows)){
            return $rows[0]->id;
        }

        $this->db->insert('ongkir_ref_district',array('name'=>trim($district),'city_id'=>$city_id));
        return $this->db->insert_id();
    }

    public function insert_reference_data($string,$column_separator){
        $line_separator = "\n";
        $csv_data = array();
        $rows = explode($line_separator,$string);
        foreach($rows as $row){
            $csv_datum = explode($column_separator,$row);
            $country = $csv_datum[0];
            $country_id = $this->insert_country($country);
            $state = $csv_datum[1];
            $state_id = $this->insert_state($state,$country_id);
            $city = $csv_datum[2];
            $city_id = $this->insert_city($city,$state_id);
            $district = $csv_datum[3];   
            $district_id = $this->insert_district($district,$city_id);
        }
        
    }

    public function empty_reference_table(){
        $this->db->truncate('ongkir_ref_country');
        $this->db->truncate('ongkir_ref_city');
        $this->db->truncate('ongkir_ref_district');
    }

    public function get_location_last_rebuilt_date(){
        $query = $this->db->get('ongkir_ref_location',1,1);    
        $rows = $query->result();
        if(empty($rows)){return '';}
        return date("l d/m/Y H:i:s",strtotime($rows[0]->last_rebuilt_date));
    }

    public function empty_location_table(){
        $this->db->truncate('ongkir_ref_location');
    }

    public function insert_district_data(){
        $this->db->select('ord.id as district_id, ord.name as district_name, orc.id as city_id, orc.name as city_name, ors.id as state_id, ors.name as state_name');        
        $this->db->from('ongkir_ref_district ord');
        $this->db->join('ongkir_ref_city orc','orc.id = ord.city_id','inner');
        $this->db->join('ongkir_ref_state ors','ors.id = orc.state_id','inner');
        $query = $this->db->get();
        $rows = $query->result();
        $last_rebuilt_date = date('Y-m-d H:i:s', now());
        foreach($rows as $row){
            $data = array(
                    'district_id'=>$row->district_id,
                    'district_name'=>$row->district_name,
                    'city_id'=>$row->city_id,
                    'city_name'=>$row->city_name,
                    'state_id'=>$row->state_id,
                    'state_name'=>$row->state_name,
                    'last_rebuilt_date'=>$last_rebuilt_date);
            $this->db->insert('ongkir_ref_location', $data);
        }
    }

    public function insert_city_data(){
        $last_rebuilt_date = date('Y-m-d H:i:s', now());

        $this->db->select('orc.id as city_id,ors.id as state_id');        
        $this->db->from('ongkir_ref_city orc');
        $this->db->join('ongkir_ref_state ors','ors.id = orc.state_id','inner');
        $query = $this->db->get();
        $rows = $query->result();
        foreach($rows as $row){
            $data = array('city_id'=>$row->city_id,'state_id'=>$row->state_id,'last_rebuilt_date'=>$last_rebuilt_date);
            $this->db->insert('ongkir_ref_location', $data);
        }
        
    }

    private function compose_data($csv_datum){
        $presentation_datum = array();
            $district = $this->guess_location($csv_datum[3],$csv_datum[2],$csv_datum[1],$csv_datum[0]);
            $presentation_datum[] = $csv_datum[0];
            $presentation_datum[] = $csv_datum[1];
            $presentation_datum[] = $csv_datum[2];
            $presentation_datum[] = $csv_datum[3];
            $presentation_datum[] = $csv_datum[4];
            $presentation_datum[] = $csv_datum[5];
            $presentation_datum[] = $csv_datum[6];
            
            if(empty($district)){
                $presentation_datum[] = array();
            }
            else{
                $presentation_datum[] = $district;
            }

        return $presentation_datum;
        
    }

    public function parse_file($path){
        $csv_data = array();
        $product = array('district_count'=>0,'ambigous_district_count'=>0,'unguessed_district_count'=>0);
        //INDONESIA#JAWA TENGAH#KAB. REMBANG#Rembang#10,000#4#0
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "#")) !== FALSE) {
                $csv_datum = $this->compose_data($data);
                $csv_data[] = $csv_datum;
                $guessed_district_count = count($csv_datum[count($csv_datum)-1]);
                $is_ambigous =  $guessed_district_count > 1;
                $is_unguessed = $guessed_district_count == 0;
                if($is_ambigous){
                    $product['ambigous_district_count']++;
                }
                else
                if($is_unguessed){
                    $product['unguessed_district_count']++;
                }
            }
            fclose($handle);
        }        
        
        $product['district_count'] = count($csv_data);
        $product['csv_data'] = $csv_data;
        return $product;
    }

    public function insert_data_to_incremented_table($selected_data,$unit_prices,$next_unit_prices
        ,$delivery_times,$destination_ids,$increment_table_name,$logistic_service_type,$logistic_company_id,$origin_id
        ,$ambigous_cities,$ambigous_districts){
        $unknown_districts_data = array();
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
        $incremented_table_name = $table_name.'_'.mdate($datestring, $time);
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

    private function look_for_location($location_name){

        $this->db->select('orl.id,ord.name as district_name,orc.name as city_name');
        $this->db->from('ongkir_ref_district ord');
        $this->db->join('ongkir_ref_city orc','orc.id = ord.city_id','inner');
        $this->db->join('ongkir_ref_state ors','ors.id = orc.state_id','inner');
        $this->db->join('ongkir_ref_location orl','orl.district_id = ord.id and orl.city_id = orc.id and orl.state_id = ors.id'
        ,'inner');

        // $this->db->where('ord.name',$location_name);
        $this->db->where("levenshtein_ratio(ord.name,".$this->db->escape($location_name).") >= 80",null,false);

        $query = $this->db->get();

        $rows = $query->result();
        if(empty($rows)){
            $this->db->select('orl.id,orc.name as city_name');
            $this->db->from('ongkir_ref_city orc');
            $this->db->join('ongkir_ref_state ors','ors.id = orc.state_id','inner');
            $this->db->join('ongkir_ref_location orl','orl.city_id = orc.id and orl.state_id = ors.id'
            ,'inner');
            // $this->db->like('orc.name',$location_name);
            $this->db->where("levenshtein_ratio(orc.name,".$this->db->escape($location_name).") >= 80",null,false);
            $this->db->where('orl.district_id',null);
            $query = $this->db->get();
            $rows = $query->result();            
        }
        

        return $rows;
    }

    private function look_for_district($district_name,$city_name){
        $select = 'orl.id,ord.name as district_name,orc.name as city_name';
        $from = 'ongkir_ref_district ord';
        $this->db->select($select);
        $this->db->from($from);
        $this->db->join('ongkir_ref_city orc','orc.id = ord.city_id','inner');
        $this->db->join('ongkir_ref_state ors','ors.id = orc.state_id','inner');
        $this->db->join('ongkir_ref_location orl','orl.district_id = ord.id and orl.city_id = orc.id and orl.state_id = ors.id'
        ,'inner');

        $query = $this->db->where(array('ord.name'=>$district_name,'orc.name'=>$city_name));
        $query = $this->db->get();

        $rows = $query->result();
        if(empty($rows)){
            $this->db->select($select);
            $this->db->from($from);
            $this->db->join('ongkir_ref_city orc','orc.id = ord.city_id','inner');
            $this->db->join('ongkir_ref_state ors','ors.id = orc.state_id','inner');
            $this->db->join('ongkir_ref_location orl','orl.district_id = ord.id and orl.city_id = orc.id and orl.state_id = ors.id'
            ,'inner');
            $query = $this->db->where('match(ord.name) against ("'.$this->db->escape_str($district_name).'")');
            $query = $this->db->where(array('orc.name'=>$city_name));
            $query = $this->db->get();
            $rows = $query->result();            
        }
        
        return $rows;
    }


    public function guess_location($district_name,$city_name,$state_name,$country_name){
        if(empty($state_name) && empty($district_name)){
            $rows = $this->look_for_location($city_name);
        }
        else{
            $rows = $this->look_for_district($district_name,$city_name);
            if(empty($rows)){
                $rows = $this->look_for_location($district_name);
            }            
        }


    	$search_result = array();
    	foreach($rows as $row){
    		$district_name = (property_exists($row,'district_name') ? $row->district_name : '');
    		$city_name = $row->city_name;
    		$search_result[$row->id] = $district_name.' ('.$row->id.'), '.$city_name;
    	}

    	return $search_result;

    }


}
?>