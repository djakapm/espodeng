<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UploadModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
	    $this->load->database();
        date_default_timezone_set('Asia/Jakarta');
    }

    // ===================
    // = DAO: TEMP TABLE =
    // ===================
    
    public function insert_temp_upload_csv($row, $id, $lookup_status, $guessed_location_id, $guessed_location_name, $guessed_location_options) {

        $this->db->insert('ongkir_temp_upload_csv',array(
            'id'=>$id,
            'country'=>$row[0],
            'state'=>$row[1],
            'city'=>$row[2],
            'district'=>$row[3],
            'price_per_kg'=>$row[4],
            'price_next_kg'=>$row[5],
            'delivery_time'=>$row[6],
            'lookup_status'=>$lookup_status,
            'guessed_location_id'=>$guessed_location_id,
            'guessed_location_name'=>$guessed_location_name,
            'guessed_options'=>$guessed_location_options
            ));
        
    }
    
    public function insert_temp_upload_csv_info($origin_id, $logistic_company, $logistic_service_type, $tableName) {
        // TRUNCATE TABLE FIRST
        $this->db->query('truncate table ongkir_temp_upload_csv_info',FALSE);
        
        $this->db->insert('ongkir_temp_upload_csv_info',array(
                'origin_id'=>$origin_id,
                'logistic_company'=>$logistic_company,
                'logistic_service_type'=>$logistic_service_type,
                'logistic_table_name'=>$tableName
                ));
    }
    
    
    public function clear_temp_upload_csv_data() {
        // TRUNCATE TEMP TABLE
        $this->db->query('truncate table ongkir_temp_upload_csv',FALSE);
    }
    
    
    public function delete_match_data() {
        $this->db->query('DELETE FROM ongkir_temp_upload_csv WHERE lookup_status = 1; ');
    }
    
    public function select_location($temp_table_id, $location_id, $location_name, $tableName, 
            $logistic_service_type, $logistic_company, $origin_id) {
        
//        error_log('about to select location');
        
        $this->db->query('
                INSERT INTO '.$tableName.' (service_type_id, company_id, origin_id, destination_id, unit_price, next_unit_price, delivery_time)
                SELECT ? service_type_id, ? company_id, ? origin_id, ? guessed_location_id, u.price_per_kg, u.price_next_kg, u.delivery_time 
                  FROM ongkir_temp_upload_csv u 
                 WHERE id = ?;', array($logistic_service_type,$logistic_company,$origin_id, $location_id, $temp_table_id));

        $this->db->query('delete from ongkir_temp_upload_csv where id = ?', array($temp_table_id));

//        error_log('last query: '. $this->db->last_query());
        
        
    }
    
    public function get_upload_info() {
        $infoQuery = $this->db->get('ongkir_temp_upload_csv_info');
        return $infoQuery->row_array();
    }
    
    public function get_all() {
        
        $query_str = "SELECT * FROM ongkir_temp_upload_csv";
        $query = $this->db->query($query_str);
        
        $config['base_url'] = base_url().'index.php/admin/preview_uploaded_data/';
        $config['total_rows'] = $query->num_rows();
        $config['per_page'] = '30';
        
        $num = $config['per_page'];
        $offset = $this->uri->segment(3);
        $offset = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
        
        if (empty($offset)) {
            $offset = 0;
        }
        
        $this->pagination->initialize($config);
        
        $data['query'] = $this->db->query($query_str. " limit $offset,$num");
        $data['base'] = $this->config->item('base_url');
        
        return $data;
    }
    
    // =========================
    // = DAO: LOGISTIC SERVICE =
    // =========================

    public function insert_match_data_to_incremented_table($tableName){
        
//        error_log('about to insert_match_data_to_incremented_table');
        
        $infoQuery = $this->db->get('ongkir_temp_upload_csv_info');
        $info = $infoQuery->row_array();
        
//        error_log('info: '.var_export($info, true));
        
        if (!empty($info)) {
            $this->db->query('
                    INSERT INTO '.$tableName.' (service_type_id, company_id, origin_id, destination_id, unit_price, next_unit_price, delivery_time)
                    SELECT ? service_type_id, ? company_id, ? origin_id, u.guessed_location_id, u.price_per_kg, u.price_next_kg, u.delivery_time 
                      FROM ongkir_temp_upload_csv u 
                     WHERE lookup_status = 1;', array($info['logistic_service_type'],$info['logistic_company'],$info['origin_id']));
            
//            error_log('last query: '. $this->db->last_query());
        }
        
    }
    
    // ==============
    // = FILE UTILS =
    // ==============
    
    public function parse_and_insert_to_temp($path){
        $csv_data = array();
        $product = array('district_count'=>0,'ambigous_district_count'=>0,'unguessed_district_count'=>0);
        //INDONESIA#JAWA TENGAH#KAB. REMBANG#Rembang#10,000#4#0
        if (($handle = fopen($path, "r")) !== FALSE) {
            
            // clear temp table first
            $this->clear_temp_upload_csv_data();
            
            $rowId = 1;
            while (($data = fgetcsv($handle, 1000, "#")) !== FALSE) {
                $lookup_status = 1; // match
                $guessed_loc_id = null;
                $guessed_loc_name = null;
                $guessed_loc_options = null;
                
                // append guessed location
                $csv_datum = $this->add_guessed_location($data);
                
                
                // add to list
                $csv_data[] = $csv_datum;
                
                // count statistics 
                $guessed_district_count = count($csv_datum[count($csv_datum)-1]);
                $is_ambigous =  $guessed_district_count > 1;
                $is_unguessed = $guessed_district_count == 0;
                if($is_ambigous){
                    $product['ambigous_district_count']++;
                    $lookup_status = 2; // choose
                }
                else
                if($is_unguessed){
                    $product['unguessed_district_count']++;
                    $lookup_status = 0; // no match
                } else if ($guessed_district_count == 1){
                    // matched
                    $row = $csv_datum[7];
                    foreach ($row as $key => $value) {
                        $guessed_loc_id = $key;
                        $guessed_loc_name = $value;
                    }
                    
                }
                
                
                // get all selection
                if ($lookup_status != 1) {
                    $guessed_loc_options = $this->serialize_districts($csv_datum[7]);
                }
                // insert into temp table
                $this->insert_temp_upload_csv($csv_datum, $rowId, $lookup_status, $guessed_loc_id, $guessed_loc_name, $guessed_loc_options);
                
                
                $rowId = $rowId + 1;
            }
            fclose($handle);
        }        
        
        $product['district_count'] = count($csv_data);
        $product['csv_data'] = $csv_data;
        return $product;
    }
    
    private function add_guessed_location($csv_datum){
        $presentation_datum = array();
        $district = $this->guess_location($csv_datum[3],$csv_datum[2]);

        $presentation_datum[] = $csv_datum[0]; // country
        $presentation_datum[] = $csv_datum[1]; // state
        $presentation_datum[] = $csv_datum[2]; // city
        $presentation_datum[] = $csv_datum[3]; // district
        $presentation_datum[] = $csv_datum[4]; // price_per_kg
        $presentation_datum[] = $csv_datum[5]; // price_next_kg
        $presentation_datum[] = $csv_datum[6]; // delivery time

        if(empty($district)){
            $presentation_datum[] = array(); // empty array shows: unguessed location
        }
        else{
            $presentation_datum[] = $district; // guessed location
        }

        return $presentation_datum;
        
    }
    
    private function serialize_districts($arr) {
        $ret = '';
        $first = true;
        foreach($arr as $k => $v) {
            if ($first) {
                $ret .= $k."=".$v;
                $first = false;
            } else {
                $ret .= '#'.$k.'='.$v;
            }
        }
        
        if (empty($ret)) {
            return null;
        } else {
            return $ret;
        }
    }

    public function guess_location($district_name,$city_name){
        
        // find best match location
        $rows = $this->look_for_location($city_name, $district_name);

        // prepare the output
    	$search_result = array();
    	foreach($rows as $row){
    		$district_name = (property_exists($row,'district_name') ? $row->district_name : '');
    		$city_name = $row->city_name;
                
                if (!empty($district_name)) {
                    $search_result[$row->id] = (!empty($district_name)?$district_name:'Kota/Kab').' ('.$row->id.'), '.$city_name;
                } else {
                    $search_result[$row->id] = $city_name . ' ('.$row->id.')';
                }
    	}

    	return $search_result;

    }
    
    private function look_for_location($city_name, $district_name){
        // config&return vars
        $final_rows = array();
        $best_match_percentage = 88;
        $minimum_match_percentage_city = 70;
        $minimum_match_percentage_district = 70;
        
        // get data from location table
        if(empty($this->district_cache)){
            $this->db->select('orl.id,orl.district_name,orl.city_name');
            $this->db->from('ongkir_ref_location orl');
            $this->db->where('orl.city_name is not null');
            $query = $this->db->get();
            $rows = $query->result();
            $this->district_cache = $rows;
            
            $this->trie_loc_cache = new Trie();
            
            foreach ($rows as $row) {
                $dn = $row->district_name;
                $cn = $row->city_name;
                
                if (empty($dn)) {
                    $dn = "-";
                } else {
                    $dn = $this->trim_district($dn);
                }
                
                if (empty($cn)) {
                    $cn = "-";
                } else {
                    // filter
                    $cn = $this->trim_city($cn);
                }
                
                $this->trie_loc_cache->add($dn."#".$cn, $row->id);
            }
        }
        else{
            $rows = $this->district_cache;
        }
        
        // remove kota and kab from city
        $city_name = $this->trim_city($city_name);
        $district_name = $this->trim_district($district_name);
        
        
        $trie_loc = (empty($district_name)?"-":$district_name)."#".(empty($city_name)?"-":$city_name);;
        
        $trie_loc_id = $this->trie_loc_cache->search($trie_loc);
        if ($trie_loc_id) {
            
            $r =  (object)array("id"=>$trie_loc_id, "district_name"=>$district_name,"city_name"=>$city_name);
            
            //error_log('oioi '. $trie_loc_id. '=>'.var_export($r, true));
            
            return array($r);
        }
        
        // filter cites for the best match
        foreach($rows as $row){
            
            // check city
            
            $dbcity_name = $this->trim_city($row->city_name);
            
            similar_text(strtolower($dbcity_name), strtolower($city_name), $percentage);

            if($percentage >= $minimum_match_percentage_city){
                $final_rows[] = $row;
            }
            
            //error_log($city_name . " => " .$dbcity_name ." [".$percentage."%]");
                
        }
        
        
        // filter again for districts to reduce suggestion
        if (!empty($district_name)) {
            // split district_name
            $district_tokens = preg_split("/[\/,]+/", $district_name);
            
            $new_final_rows = array();
            
            $exit_loop = false;
            foreach($final_rows as $row) {
                if (isset($row->district_name)) {
                    
                    $tmp_district_name = strtolower($this->trim_district($row->district_name));
                    
                    // check full district name
                    similar_text($tmp_district_name, strtolower($district_name), $percentage);
                    
                    if($percentage >= $best_match_percentage){
                        // great we found best match just return this single row;
                        $new_final_rows = array();
                        $new_final_rows[] = $row;
                        break;
                    } else {
                        
                        if ($percentage >= $minimum_match_percentage_district) {
                            //error_log($district_name . " > ". $tmp_district_name ." [".$percentage."]");
                            $new_final_rows[] = $row;
                        } else {
                            // too low match percentage, try tokenizing
                            foreach ($district_tokens as $district_token) {

                                similar_text($tmp_district_name, strtolower($district_token), $percentage);

                                if($percentage >= $best_match_percentage){
                                    // great we found best match just return this single row;
                                    $new_final_rows = array();
                                    $new_final_rows[] = $row;
                                    $exit_loop = true;
                                    break;
                                }
                                else
                                if ($percentage >= $minimum_match_percentage_district) {
                                    $new_final_rows[] = $row;
                                }
                            }
                            
                            if ($exit_loop) {
                                break;
                            }
                            // end tokenize
                        }
                        
                    }
                    
                    
                } else {
                    // just include city with no district
                    $new_final_rows[] = $row;
                }
            }
            
            $final_rows = $new_final_rows;
            
        } else {
            // no district, return city with no district
            // with the best match only (if exists) 
            // this will reduce suggestion number
            $max_p = $minimum_match_percentage_city;
            $new_final_rows = array();
            foreach($rows as $row){

                if (empty($row->district_name)) {
                    
                    $dbcity_name = $this->trim_city($row->city_name);
                    
                    // check city
                    similar_text(strtolower($dbcity_name), strtolower($city_name), $percentage);
                    
                    if ($percentage > $max_p) {
                        $new_final_rows = array();
                        $new_final_rows[] = $row;
                        $max_p = $percentage;
                        
                        //error_log ($dbcity_name . ' => ' . $city_name);
                    }
                    
                }

            }
            
            
            $final_rows = $new_final_rows;
        }
        
        return $final_rows;
    }

    private function trim_city($city_name) {
        $ret = trim(preg_replace('/((kota)|(dki)|(daerah)|(khusus)|(administrasi)|(istimewa))+/i','',$city_name));
        $ret = trim(preg_replace('/((kabupaten))+/i','kab.',$ret));
        return $ret;
    }
    
    private function trim_district($district_name) {
        return trim(preg_replace('/((kec\\.)|(kec)|(kepulauan)|(kota))+/i','',$district_name));
    }

}
?>