<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eula extends Public_Controller {
	public function after_init() {
		$this->set_scripts_and_styles();
	}

	public function index() {

		$this->_data['title']  = "BambuPay Mobile Application End User License Agreement (EULA)";
		$this->set_template("eula/index", $this->_data);
	}
}

