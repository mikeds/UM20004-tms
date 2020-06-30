<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_404 extends Global_Controller {

	public function after_init(){
 		$this->_today = date("Y-m-d H:i:s");
	}

	public function index() {
        http_response_code(404);
	}
}




















