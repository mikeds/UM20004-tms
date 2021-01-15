<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terms_and_conditions extends Public_Controller {
	public function after_init() {
		$this->set_scripts_and_styles();
	}

	public function index() {

		$this->_data['title']  = "BAMBUPAY MOBILE APP AND WEBSITE TERMS & CONDITIONS";
		$this->set_template("tnc/index", $this->_data);
	}
}

