<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		// $this->load->library('recaptcha');
		// $this->lang->load('recaptcha');
		$data = array();
		$data['site_name'] = 'palingoke.info';
		$data['site_title'] = 'Ongkos Kirim Paling Oke';
		// $data['recaptcha'] = $this->recaptcha->get_html();
		$this->load->view('welcome_message',$data);
	}

	public function disclaimer(){
		$data = array();
		$data['site_name'] = 'palingoke.info';
		$data['site_title'] = 'Ongkos Kirim Paling Oke';
		$this->load->view('disclaimer_page',$data);
	}

	public function news(){
		$data = array();
		$data['site_name'] = 'palingoke.info';
		$data['site_title'] = 'Ongkos Kirim Paling Oke';
		$this->load->view('news_page',$data);
	}

}
