<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ValidationModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
		$this->load->library('recaptcha');

    }

	public function check_captcha($challenge,$response) {
	  if ($this->recaptcha->check_answer($this->input->ip_address(),$challenge,$response)) {
	    return TRUE;
	  } else {
	    return FALSE;
	  }
	}

}
?>