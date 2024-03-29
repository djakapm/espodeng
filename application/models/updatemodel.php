<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'trie.class.php';

class UpdateModel extends CI_Model {

    private $district_cache = array();
    private $city_cache = array();
    private $trie_loc_cache;

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
        $this->db->truncate('ongkir_ref_state');
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

        $this->db->select('orc.id as city_id,ors.id as state_id, orc.name as city_name, ors.name as state_name');        
        $this->db->from('ongkir_ref_city orc');
        $this->db->join('ongkir_ref_state ors','ors.id = orc.state_id','inner');
        $this->db->where("lower(orc.name) not like 'kab.%'");
        $this->db->where("lower(orc.name) not like 'kabupaten%'");
        
        $query = $this->db->get();
        $rows = $query->result();
        foreach($rows as $row){
            $data = array('state_name'=>$row->state_name,'city_name'=>$row->city_name,'city_id'=>$row->city_id,'state_id'=>$row->state_id,'last_rebuilt_date'=>$last_rebuilt_date);
            $this->db->insert('ongkir_ref_location', $data);
        }
        
    }

    private function compose_data($csv_datum){
        $presentation_datum = array();
        $district = $this->guess_location($csv_datum[3],$csv_datum[2]);

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

    public function create_or_replace_incremented_table($incremented_table_name){
        $this->db->query('drop table if exists '.$incremented_table_name,FALSE);

         $query = 'create table '.$incremented_table_name.' (';
         $query .= 'id int(11) not null auto_increment,';
         $query .= 'service_type_id int(2) not null,';
         $query .= 'company_id int(11) not null,';
         $query .= 'origin_id int(11) not null,';
         $query .= 'destination_id int(11) not null,';
         $query .= 'unit_price decimal(10,0) not null,';
         $query .= 'next_unit_price int(11) not null,';
         $query .= 'delivery_time varchar(10) not null,';
         $query .= 'primary key (id)';
         $query .= ') engine=myisam auto_increment=0 default charset=utf8 comment="logistic service"';


        $this->db->query($query,FALSE);        
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

    private function look_for_city($city_name){
        $final_rows = array();
        $minimum_match_percentage = 80;

        if(empty($this->city_cache)){
            $this->db->select('orl.id,orl.city_name');
            $this->db->from('ongkir_ref_location orl');
            $this->db->or_where('orl.district_id',null);
            $query = $this->db->get();
            $rows = $query->result();
            $this->city_cache = $rows;
        }
        else{
            $rows = $this->city_cache;
        }


        foreach($rows as $row){
            similar_text(strtolower($row->city_name), strtolower($city_name), $percentage);
            if($percentage == 100){
                $final_rows = array();
                $final_rows[] = $row;
                return $final_rows;
            }
            else
            if($percentage >= $minimum_match_percentage){
                $final_rows[] = $row;
            }
        }
        

        return $final_rows;
    }

    private function look_for_district($district_name){
        $final_rows = array();
        $minimum_match_percentage = 70;

        if(empty($this->district_cache)){
            $this->db->select('orl.id,orl.district_name,orl.city_name');
            $this->db->from('ongkir_ref_location orl');
            $query = $this->db->get();
            $rows = $query->result();
            $this->district_cache = $rows;
        }
        else{
            $rows = $this->district_cache;
        }
        

        foreach($rows as $row){
            similar_text(strtolower($row->district_name), strtolower($district_name), $percentage);

            if($percentage == 100){
                $final_rows = array();
                $final_rows[] = $row;
                return $final_rows;
            }
            else
            if($percentage >= $minimum_match_percentage){
                $final_rows[] = $row;
            }
        }

        
        return $final_rows;
    }
    
    public function insert_data_to_origin_table($origin_id){
       $query = $this->db->get_where('ongkir_ref_location ', array('id' => $origin_id));
       $rows = $query->result();
       $row = $rows[0];
       $name = (empty($row->district_name)? $row->city_name : $row->district_name);

       $query = $this->db->get_where('ongkir_ref_origin_location',array('name'=>$name));
       $rows = $query->result();
       if(!empty($rows)){
           $this->db->delete('ongkir_ref_origin_location',array('name'=>$name));
       }

       $this->db->insert('ongkir_ref_origin_location',array('location_id'=>$origin_id,'name'=>$name));
  }


}
?>