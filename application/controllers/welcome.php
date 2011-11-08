<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	private $data = array();


	function __construct()
	{
	    parent::__construct();
		$this->data['site_name'] = 'palingoke.info';
		$this->data['site_title'] = 'Ongkir';

	}
		
	public function index()
	{
	    $this->load->library('recaptcha');
	    $this->lang->load('recaptcha');
    
    	$this->data['recaptcha'] = $this->recaptcha->get_html();
    	$this->load->view('welcome_message',$this->data);
	}

	public function about(){
		$this->load->view('about_page',$this->data);
	}

	public function contactus(){
		$this->load->view('contactus_page',$this->data);
	}

	public function disclaimer(){
		$this->load->view('disclaimer_page',$this->data);
	}

	public function news(){
		$this->load->view('news_page',$this->data);
	}

}
