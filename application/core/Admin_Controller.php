<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CMS_Controller class
 * Base controller ?
 *
 * @author Marknel Pineda
 */
class Admin_Controller extends Global_Controller {

	private 
		$_stylesheets = array(),
		$_scripts = array();
	
	protected
		$_base_controller = "admin",
		$_base_session = "session",
		$_data = array(), // shared data with child controller
		$_user_data = NULL,
		$_limit = 20;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize all configs, helpers, libraries from parent
		parent::__construct();
		date_default_timezone_set("Asia/Manila");
		$this->_today = date("Y-m-d H:i:s");

		$this->validate_login();
		$this->set_user_data();
		$this->setup_nav_sidebar_menu();
		$this->after_init();
	}

	private function set_user_data() {
		$this->_user_data = $this->get_user();
		is_null($this->_user_data) ? redirect(base_url() . "logout") : "";
	}

	public function get_user() {
		$this->load->model('admin/merchants_model', 'merchants');
		$this->load->model('admin/wallets_model', 'wallets');

		$session = $this->session->userdata("{$this->_base_session}");
		if (empty($session)) {
			redirect(base_url() . "logout");
		}

		$merchant_id = isset($session['merchant_id']) ? $session['merchant_id'] : "";
		$datum_merchant = $this->merchants->get_datum(
			'',
			array(
				'merchant_id' => $merchant_id
			)
		)->row();

		if ($datum_merchant == "") {
			redirect(base_url() . "logout");
		}

		$bridge_id = $datum_merchant->oauth_client_bridge_id;

		$datum_wallet = $this->wallets->get_datum(
			'',
			array(
				'oauth_client_bridge_id' => $bridge_id
			)
		)->row();

		if ($datum_wallet == "") {
			redirect(base_url() . "logout");
		}

		return array(
			'merchant_row' => $datum_merchant,
			'wallet_row' => $datum_wallet
		);
	}

	public function validate_login() {
		$login_url = base_url() . "login";

        $controller = strtolower(get_controller());
		if(empty($this->session->userdata("{$this->_base_session}")) && $controller != 'login' ) {
            $this->session->unset_userdata("{$this->_base_session}");
			$this->session->sess_destroy();
            redirect($login_url);
        } else if(!empty($this->session->userdata("{$this->_base_session}"))) {
			$member_session = $this->session->userdata("{$this->_base_session}");
		}
	}

	/**
	 * Generate Menu UI
	 *
	 */
	private function setup_nav_sidebar_menu() {
		$this->_data['logout_url'] = base_url() . "logout";

		$menu_items = array();

		$menu_items[] = array(
			'menu_id'			=> 'dashboard',
			'menu_title'		=> 'Dashboard',
			'menu_url'			=> 	base_url(),
			'menu_controller'	=> 'dashboard',
			'menu_icon'			=> 'view-dashboard',
			// 'menu_sub_items'	=> array(
			// 	array(
			// 		'menu_title'		=> 'Sub Menu Sample',
			// 		'menu_url'			=> 	base_url(),
			// 		'menu_controller'	=> 'dashboard',
			// 	)
			// )
		);

		$menu_items[] = array(
			'menu_id'			=> 'transactions',
			'menu_title'		=> 'Transactions',
			// 'menu_url'			=> 	base_url() . "transactions",
			'menu_controller'	=> 'transactions',
			'menu_icon'			=> 'view-dashboard',
			'menu_sub_items'	=> array(
				array(
					'menu_title'		=> 'Cash In',
					'menu_url'			=> 	base_url() . "transactions/cash-in",
					'menu_controller'	=> 'transactions',
				),
				array(
					'menu_title'		=> 'Cash Out',
					'menu_url'			=> 	base_url() . "transactions/cash-out",
					'menu_controller'	=> 'transactions',
				)
			)
		);

		$menu_items[] = array(
			'menu_id'			=> 'profile',
			'menu_title'		=> 'Profile',
			'menu_url'			=> 	base_url() . "profile",
			'menu_controller'	=> 'profile',
			'menu_icon'			=> 'view-dashboard',
		);

		$this->_data['nav_sidebar_menu'] = $this->generate_sidebar_items($menu_items);
	}

	public function set_scripts_and_styles() {
		$this->add_styles(base_url() . "assets/frameworks/majestic-admin/vendors/mdi/css/materialdesignicons.min.css", true);
		$this->add_styles(base_url() . "assets/frameworks/majestic-admin/vendors/base/vendor.bundle.base.css", true);
		$this->add_styles(base_url() . "assets/frameworks/majestic-admin/css/style.css", true);
		$this->add_styles(base_url() . "assets/{$this->_base_controller}/css/style.css", true);

		// inject:js
		$this->add_scripts(base_url() . "assets/frameworks/majestic-admin/vendors/base/vendor.bundle.base.js", true);
		$this->add_scripts(base_url() . "assets/frameworks/majestic-admin/js/off-canvas.js", true);
		$this->add_scripts(base_url() . "assets/frameworks/majestic-admin/js/hoverable-collapse.js", true);
		$this->add_scripts(base_url() . "assets/frameworks/majestic-admin/js/template.js", true);
		// endinject

		$this->add_scripts(base_url() . "assets/{$this->_base_controller}/js/scripts.js", true);
	}
}
