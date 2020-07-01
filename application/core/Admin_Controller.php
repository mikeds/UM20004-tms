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
		$_data = array(); // shared data with child controller

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize all configs, helpers, libraries from parent
		parent::__construct();
		$this->validate_login();
		$this->setup_nav_sidebar_menu();
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
			'menu_id'			=> 'dictionaries',
			'menu_title'		=> 'Dictionaries',
			'menu_url'			=> 	base_url() . "dictionaries",
			'menu_controller'	=> 'dictionaries',
			'menu_icon'			=> 'view-dashboard',
		);

		$menu_items[] = array(
			'menu_id'			=> 'places',
			'menu_title'		=> 'Places',
			'menu_url'			=> 	base_url() . "places",
			'menu_controller'	=> 'places',
			'menu_icon'			=> 'view-dashboard',
		);

		$menu_items[] = array(
			'menu_id'			=> 'user-management',
			'menu_title'		=> 'User Management',
			'menu_url'			=> 	base_url() . "user-management",
			'menu_controller'	=> 'user_management',
			'menu_icon'			=> 'view-dashboard',
		);

		$this->_data['nav_sidebar_menu'] = $this->generate_sidebar_items($menu_items);
	}

	private function generate_sidebar_items($menu_items) {
		$controller = get_controller();
		$controller = strtolower($controller);

		$sidebar_items 	= "";

		if (count($menu_items) != 0) {
			foreach ($menu_items as $key => $item) {
				$status	= "";

				if ($controller == $item['menu_controller']) {
					$status = "active";
				}

				$attributes['item'] 	= $item;
				$attributes['status'] 	= $status;

				if (isset($item['menu_sub_items'])) {
					$attributes['sub_menu']	= $this->generate_sidebar_sub_menu($item);
					
					$sidebar_items .= $this->load->view("templates/sidebar/sidebar-item-with-sub-menu", $attributes, true);
				} else {
					$sidebar_items .= $this->load->view("templates/sidebar/sidebar-item", $attributes, true);
				}
			}
		}
		
		return $sidebar_items;
	}

	private function generate_sidebar_sub_menu($main_item) {
		$menu_id 	= $main_item['menu_id'];
		$menu_items = $main_item['menu_sub_items'];

		$controller = get_controller();
		$controller = strtolower($controller);

		$menu = "";
		$sub_items = "";

		if (count($menu_items) != 0) {
			foreach ($menu_items as $key => $item) {
				$status	= "";

				if ($controller == $item['menu_controller']) {
					$status = "active";
				}

				$attributes['item'] 	= $item;
				$attributes['status'] 	= $status;

				$sub_items .= $this->load->view("templates/sidebar/sidebar-sub-menu-item", $attributes, true);
			}

			$menu_attributes['items'] 	= $sub_items;
			$menu_attributes['menu_id'] = $menu_id;

			$menu = $this->load->view("templates/sidebar/sidebar-sub-menu", $menu_attributes, true);
		}

		return $menu;
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
