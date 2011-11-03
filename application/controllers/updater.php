<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Updater extends CI_Controller {

	function __construct()
	{
	    parent::__construct();
	    $this->load->database();
	}

	public function index(){
		// $this->_insert_ref_location();
	}

	private function _insert_ref_location(){
    	$this->db->select('district.id district_id,city.id city_id,state.id state_id');
		$this->db->from('ongkir_logistic_service ols');
    	$this->db->join('ongkir_ref_district district','district.id = ols.destination_id','inner');
    	$this->db->join('ongkir_ref_city city','city.id = district.city_id','inner');
    	$this->db->join('ongkir_ref_state state','state.id = city.state_id','inner');
    	$query = $this->db->get();
    	foreach($query->result() as $row){
    		echo 'insert into ongkir_ref_location (district_id,city_id,state_id) values('.$row->district_id.','.$row->city_id.','.$row->state_id.')<br/>';
    	}


	}

	private function _update_district(){
		$data = array(
					'Balaraja#21000#17000#1',
					'Balikpapan#31000#31000#1',
					'Banda Aceh#30000#30000#2',
					'Bandar Lampung#18000#18000#1',
					'Bandung#17000#17000#1',
					'Banjarbaru#27000#27000#2',
					'Banjarmasin#24000#24000#1',
					'Banyumulek#27000#27000#2',
					'Batu Sangkar#34000#24000#2',
					'Baturaja#42000#26000#2',
					'Beber#24000#19000#1',
					'Bekasi#14000#14000#1',
					'Belawan#37000#30000#2',
					'Belinyu#32000#32000#2',
					'Bengkulu#21000#21000#1',
					'Binjai#37000#28000#2',
					'Bintaro#21000#17000#1',
					'Bitung#45000#45000#2',
					'Bogor#14000#14000#1',
					'Bontang#41000#41000#2',
					'Boyolali#24000#21000#2',
					'Branti#23000#21000#1',
					'Bukit Tinggi#32000#24000#2',
					'Ciampea#28000#17000#2',
					'Cianjur#21000#21000#2',
					'Ciapus (BGR)#18000#16000#1',
					'Ciawi (BGR)#18000#16000#1',
					'Cibinong#18000#16000#2',
					'Cicurug#21000#21000#2',
					'Cigombong#35000#18000#1',
					'Cikampek#21000#17000#2',
					'Cikupa#21000#17000#2',
					'Ciledug - CBN#29000#20000#1',
					'Ciledug - TGR#21000#17000#1',
					'Cilegon#17000#17000#1',
					'Cileungsi#28000#17000#2',
					'Cilimus#24000#19000#1',
					'Ciluar#18000#16000#1',
					'Cimahi#26000#20000#1',
					'Cikarang#17000#17000#2',
					'Cilacap#18000#18000#2',
					'Ciperna#22000#18000#1',
					'Ciputat#21000#17000#1',
					'Cirebon#17000#17000#1',
					'Cisaat#23000#23000#1',
					'Cisarua (BGR)#25000#17000#2',
					'Cisoka#21000#17000#1',
					'Citeureup#25000#17000#1',
					'Ciwaringin#26000#19000#1',
					'Dadap/ kosambi#18000#16000#1',
					'Dayeuh Kolot#24000#19000#2',
					'Delanggu#29000#24000#1',
					'Denpasar#20000#20000#1',
					'Depok#14000#14000#1',
					'DKI Jakarta#12000#12000#1',
					'Gerung#27000#27000#2',
					'Gunung Guruh#23000#20000#1',
					'Gunung Jati#22000#18000#1',
					'Gunung Putri#25000#17000#1',
					'Gunung Sindur#35000#18000#1',
					'Jambi#21000#21000#1',
					'Jatiwangi#26000#19000#2',
					'Jogyakarta#18000#18000#1',
					'Jombang (SUB)#40000#22000#1',
					'Jonggol#35000#18000#2',
					'Jurang Manggu#21000#17000#1',
					'Kadipaten#27000#20000#2',
					'Kapetakan#24000#19000#1',
					'Karang Anyar (SOC)#22000#20000#2',
					'Karang Suwung#26000#19000#2',
					'Karawang#15000#15000#1',
					'Kartasura#20000#20000#2',
					'Kediri (SUB)#40000#24000#2',
					'Kerandangan#27000#27000#2',
					'Klangenan#24000#19000#2',
					'Klari#21000#17000#1',
					'Klaten#24000#21000#2',
					'Klayan#22000#18000#2',
					'Kragilan#20000#20000#2',
					'Kronjo#21000#17000#2',
					'Kudus Via SRG#21000#21000#2',
					'Kuningan#26000#19000#1',
					'Kuta - Bali#24000#22000#2',
					'Lahat#42000#31000#2',
					'Legok#21000#17000#2',
					'Lembar#29000#26000#2',
					'Leuwi Liang/Karacak#28000#17000#2',
					'Lido#35000#18000#2',
					'Lingsar#27000#26000#2',
					'Madiun Via SOC#18000#18000#2',
					'Magelang#23000#21000#2',
					'Makassar#31000#31000#1',
					'Malang#21000#21000#2',
					'Manado#42000#42000#2',
					'Mantang#28000#27000#2',
					'Margahayu#24000#20000#1',
					'Martapura ( BDJ )#29000#27000#2',
					'Mataram Via DPS#22000#22000#1',
					'Mauk#21000#17000#2',
					'Mayung#26000#19000#2',
					'Medan#24000#24000#1',
					'Megamendung#25000#17000#2',
					'Mentok#32000#32000#2',
					'Merak#21000#20000#2',
					'Mojokerto#28000#22000#2',
					'Mundu Pesisir#24000#19000#2',
					'Narmada#28000#26000#2',
					'Natar#23000#21000#2',
					'Padang#22000#22000#1',
					'Pakem#28000#18000#2',
					'Palangkaraya#26000#26000#2',
					'Palembang#21000#21000#1',
					'Palimanan#26000#19000#2',
					'Pamulang#21000#17000#1',
					'Pangkal Pinang#23000#23000#1',
					'Panjang#21000#20000#1',
					'Pasar Kemis#21000#17000#1',
					'Pekanbaru#22000#22000#1',
					'Pekik Nyaring#26000#23000#2',
					'Perumnas Palur#26000#23000#2',
					'Pd Cabe Udik#21000#17000#2',
					'Pd Kacang#21000#17000#1',
					'Pd Pucung#21000#17000#2',
					'Penggung#22000#18000#1',
					'Playen / Gn Kidul#29000#18000#2',
					'Plered (CBN)#22000#18000#2',
					'Pontianak#23000#23000#1',
					'Prambanan (JOG)#29000#18000#2',
					'Pulau Batam#22000#22000#1',
					'Purwakarta#16000#16000#1',
					'Purwokerto#18000#18000#2',
					'Puyung Leneng#29000#26000#2',
					'Raya Bogor - Km 39#18000#16000#1',
					'Rengas Dengklok#22000#17000#2',
					'Rumak#27000#27000#2',
					'Salabintana#22000#20000#2',
					'Salatiga Via SOC#21000#21000#2',
					'Samarinda#39000#39000#1',
					'Sawah Baru#21000#17000#1',
					'Semarang#18000#18000#1',
					'Senggigi#28000#26000#2',
					'Sentul (BGR)#25000#17000#2',
					'Serang#15000#15000#1',
					'Serpong#18000#16000#2',
					'Sidoarjo#24000#21000#2',
					'Sleman#28000#18000#2',
					'Solo#18000#18000#1',
					'Sragen#24000#21000#2',
					'Sukabumi#17000#17000#1',
					'Sukamantri#18000#16000#2',
					'Sukaraja (SMI)#22000#20000#2',
					'Sukoharjo (SOC)#22000#20000#2',
					'Sumber#22000#19000#1',
					'Sungai Gerong#32000#28000#2',
					'Surabaya#19000#19000#1',
					'Tangerang#14000#14000#1',
					'Tasikmalaya#18000#18000#2',
					'Telajung Udik#25000#17000#2',
					'Teluk Jambe#21000#17000#1',
					'Teluk Naga#21000#17000#1',
					'Tiga Raksa#21000#17000#2',
					'Ujung Berung#24000#20000#1',
					'Wates#29000#18000#2',
					'Wonogiri#24000#21000#2',
					'Wonosari Kota#29000#18000#2'


		);
		$with_id = "";
		$without_id = "";
		foreach($data as $datum){
		$district = explode('#',$datum);	
		$district_table = 'ongkir_ref_district';
		$district_query = $this->db->get_where($district_table,array('name'=>$district[0]));
		$district_results = $district_query->result();
			if(empty($district_results)){
				$without_id .= $datum.'<br/>';				
				
			}
			else{
				$destination_id = $district_results[0]->id;
				$with_id .= $destination_id.','.$datum.'<br/>';
			}

		}

			echo $with_id;
			echo '<hr/><br/><br/>';
			echo $without_id;

	}

	public function create_data(){
		$this->load->helper('file');
		$string = read_file('./upload/jne-2011.csv');
		$rows =  explode('|',$string);
		foreach($rows as $row){
			$items = explode('#',$row);

			if(count($items) == 1){
				continue;
			}

			$country = $items[0];
			$state = $items[1];
			$city = $items[2];
			$district = $items[3];
			$unit_price = str_replace(',','',$items[4]);
			$delivery_time = $items[5];

			$this->_create_state_data($state);
			$this->_create_city_data($city,$state);
			$this->_create_district_data($district,$city);
			$this->_create_logistic_service_data($district,$unit_price,$delivery_time);
		}
	}

	private function _create_logistic_service_data($district,$unit_price,$delivery_time){
		$origin_id = 367;//Tanah Abang
		$company_id = 1;//JNE
		$district_table = 'ongkir_ref_district';
		$district_query = $this->db->get_where($district_table,array('name'=>$district));
		$district_results = $district_query->result();
		$destination_id = $district_results[0]->id;
		$logistic_service_query = $this->db->get_where('ongkir_logistic_service',array('company_id'=>$company_id,
		'origin_id'=>$origin_id,'destination_id'=>$destination_id));
		$logistic_service_results = $logistic_service_query->result();
		if(empty($logistic_service_results)){
			$this->db->insert('ongkir_logistic_service',array('name'=>'Reguler',
			'company_id'=>$company_id,'origin_id'=>$origin_id,
			'destination_id'=>$destination_id,'unit_price'=>$unit_price,'delivery_time'=>$delivery_time));			
		}
		else{
			$this->db->where('id', $logistic_service_results->id);
			$this->db->update('ongkir_logistic_service', array('unit_price'=>$unit_price,'delivery_time'=>$delivery_time)); 
		}
	}

	private function _create_district_data($district,$city){
		$city_table = 'ongkir_ref_city';
		$district_table = 'ongkir_ref_district';
		$district_query = $this->db->get_where($district_table,array('name'=>$district));
		$district_results = $district_query->result();

		if(empty($district_results)){
			$city_query = $this->db->get_where($city_table,array('name'=>$city));
			$city_results = $city_query->result();
			echo 'inserting '.$district.' to '.$district_table.'<br/>';
			$this->db->insert($district_table,array('name'=>$district,'city_id'=>$city_results[0]->id));
		}
	}

	public function _create_city_data($city,$state){
		$state_table = 'ongkir_ref_state';
		$city_table = 'ongkir_ref_city';
		$city_query = $this->db->get_where($city_table,array('name'=>$city));
		$city_results = $city_query->result();

		if(empty($city_results)){
			$state_query = $this->db->get_where($state_table,array('name'=>$state));
			$state_results = $state_query->result();
			echo 'inserting '.$city.' to '.$city_table.'<br/>';
			$this->db->insert($city_table,array('name'=>$city,'state_id'=>$state_results[0]->id));
		}
	}

	private function _create_state_data($state){
		if(empty($state)){return;}
		$table = 'ongkir_ref_state';
		$query = $this->db->get_where($table,array('name'=>$state));
		$results = $query->result();
		if(empty($results)){
			echo 'inserting '.$state.' to '.$table.'<br/>';
			$this->db->insert($table,array('name'=>$state,'country_id'=>1));
		}
	}

}