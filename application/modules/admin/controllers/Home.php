<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Global_Controller {

	public function after_init() {}

	public function index() {
		echo "Welcome to Bambupay TMS";
	}
	
}
