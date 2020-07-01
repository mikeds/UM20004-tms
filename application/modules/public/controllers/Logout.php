<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends Global_Controller {
	public function after_init() {
		$this->_today = date("Y-m-d H:i:s");
	}

	public function index(){
		$this->session->unset_userdata('member_session');
		$this->session->sess_destroy();
		redirect(base_url() . "login");
	}
}




























