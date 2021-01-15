<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_privacy_statement extends Public_Controller {
	public function after_init() {
		$this->set_scripts_and_styles();
	}

	public function index() {

		$this->_data['title']  = "BambuPay Data Privacy Statement";
		$this->set_template("data_privacy_statement/index", $this->_data);
	}
}

