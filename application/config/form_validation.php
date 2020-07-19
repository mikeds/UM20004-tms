<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form validation rules by on controller
 *
 */
$default_rules 		= "trim|alpha_numeric_spaces|xss_clean";
$required_rules 	= "trim|required|alpha_numeric_spaces|xss_clean";

$default_numeric_rules 	= "trim|numeric|xss_clean";
$required_numeric_rules = "trim|required|numeric|xss_clean";

$default_alpha_rules 	= "trim|alpha|xss_clean";
$required_alpha_rules 	= "trim|required|alpha|xss_clean";

$default_alphanumeric_rules 	= "trim|alpha_numeric|xss_clean";
$required_alphanumeric_rules 	= "trim|required|alpha_numeric|xss_clean";

$required_email_rules 	= "trim|required|valid_email|xss_clean";

switch( strtolower(get_controller()) ) {
	case 'login' : 
		$config = array(
			'login' => array(
				array( 	
					'field' => 'username',
					'label' => 'Username',
					'rules'	=> 'trim|required|xss_clean'
				),
				array( 	
					'field' => 'password',
					'label' => 'Password',
					'rules'	=> 'trim|required|min_length[6]|xss_clean'
				)
			),
		);
	break;

	case 'marketplace' : 
		$config = array(
			'product_new' => array(
				array( 	
					'field' => 'category',
					'label' => 'Category',
					'rules'	=> 'trim|required|xss_clean|numeric'
				),
				array( 	
					'field' => 'product-name',
					'label' => 'Product Name',
					'rules'	=> 'trim|required|xss_clean'
				),
				array( 	
					'field' => 'product-description',
					'label' => 'Product Description',
					'rules'	=> 'trim|required|xss_clean'
				)
			),
		);
	break;

	default : $config = array();
}


// pre( $config );

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */