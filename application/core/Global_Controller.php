<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CMS_Controller class
 * Base controller ?
 *
 * @author Marknel Pineda
 */
class Global_Controller extends MX_Controller {
	private
		$_today = "",
		$_stylesheets = array(),
		$_scripts = array();

	public function __construct() {
		// Initialize all configs, helpers, libraries from parent
		parent::__construct();
		$this->init();
	}

	private function init() {
		// set current datetime
		$this->_today = date("Y-m-d H:i:s");
		date_default_timezone_set( "Asia/Manila" );

		$this->after_init();
	}

	// To override on child class
	private function after_init() {}

	/**
	 * Returns generated notification UI
	 *
	 */
	public function generate_notification($class = "default", $message = "") {
		return '<div class="alert alert-'. $class .'">'. $message .'</div>';
	}

	/**
	 * Returns all CSS files added
	 *
	 */
	public function get_styles() {
		return $this->_stylesheets;
	}
	
	/**
	 * Returns all JS files added
	 *
	 */
	public function get_scripts() {
		return $this->_scripts;
	}

	// add styles
	public function add_styles( $css_files, $external = false, $core_path = "main" ) {
		if(!$external){
			$css_path = base_url() . 'assets/'. $core_path .'/css' . DIR_SEPARATOR;
		}

		if( is_array( $css_files ) ) {
			foreach( $css_files as $css ) {
				
				if( !$external ){
					$this->_stylesheets[] = $css_path . $css_files . '.css';
				}else{
					$this->_stylesheets[] = $css_files;
				}
			}
		}else{
			if( !$external ){
				$this->_stylesheets[] = $css_path . $css_files . '.css';
			}else{
				$this->_stylesheets[] = $css_files;
			}
		}
		return $this;
	}

	// add scripts
	public function add_scripts( $js_files, $external = false, $core_path = "main" ) {
		if(!$external){
			$js_path = base_url() . 'assets/'.$core_path.'/js' . DIR_SEPARATOR;
		}
		
		if( is_array( $js_files ) ) {
			foreach( $js_files as $js ) {
				if( !$external ) { $this->_scripts[] = $js_path . $js_files . '.js'; }
				else { $this->_stylesheets[] = $js; }
			}
		} else {
			if( !$external ) { $this->_scripts[] = $js_path . $js_files . '.js'; }
			else { $this->_scripts[] = $js_files; }
		}
		return $this;
	}

	/**
	 * Calls header, footer templates to build "Master"
	 *
	 */ 
	public function set_template( $template_file, $data = array(),  $customer_theme_path = array(), $hasTemplates = true, $core_path='')
	{

		// get stylesheets and javascripts
		 $data[ 'stylesheets' ] = $this->get_styles();
		 $data[ 'javascripts' ] = $this->get_scripts();
		
		 if ($hasTemplates) {
			if (count($customer_theme_path) > 0) {
				$header_template_path = isset($customer_theme_path['header_template_path']) ? $customer_theme_path['header_template_path'] : "/templates/header";
				$footer_template_path = isset($customer_theme_path['footer_template_path']) ? $customer_theme_path['footer_template_path'] : "/templates/footer";;
	
				$this->load->view( $header_template_path, $data );
				$this->load->view( $template_file, $data );
				$this->load->view( $footer_template_path, $data );
				return;
			} 

			$this->load->view( $core_path.'/templates/header', $data );
			$this->load->view( $template_file, $data );
			$this->load->view( $core_path.'/templates/footer', $data );
			
		 } else {
		 	$this->load->view( $template_file, $data );
		 }
	}
}