<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function after_init() {
		$this->set_scripts_and_styles();
		$this->load->model('admin/merchants_model', 'merchants');
	}

	public function index() {
		$this->_data['title']  = "Dashboard";
		$this->set_template("dashboard/index", $this->_data);
	}
}
