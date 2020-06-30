<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CMS_Controller class
 * Base controller ?
 *
 * @author Marknel Pineda
 */
class Global_Controller extends MX_Controller {
	public function __construct() {
		// Initialize all configs, helpers, libraries from parent
		parent::__construct();
		$this->init();
	}

	private function init() {
		$this->after_init();
	}
}