<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qr_code extends Admin_Controller {
		
	public function after_init() {}

	public function index( $str = "" ) {
        $this->load->library('ciqrcode');
        header("Content-Type: image/png");
        $params['data'] = $str;
        $params['size'] = 6;
        $config['cacheable']	= false; //boolean, the default is true
        $config['cachedir']		= ''; //string, the default is application/cache/
        $config['errorlog']		= ''; //string, the default is application/logs/
        $this->ciqrcode->generate($params);
    }
}




















