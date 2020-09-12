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

	protected
		$_upload_path = FCPATH . UPLOAD_PATH,
		$_ssl_method = "AES-128-ECB";

	public function __construct() {
		// Initialize all configs, helpers, libraries from parent
		parent::__construct();
		$this->init();
	}

	private function init() {
		// set current datetime
		date_default_timezone_set("Asia/Manila");
		$this->_today = date("Y-m-d H:i:s");

		$this->after_init();
	}

	// To override on child class
	private function after_init() {}

	/**
	 * Returns generated selection
	 *
	 */
	public function generate_selection($name, $data, $id_selected, $id_key = "id", $name_key = "name", $has_preselected = false, $default_placeholder = "Please select") {
		$first = '<select id="'. $name .'" name="'. $name .'"  class="form-control">';
		$body = '<option value="">' . $default_placeholder . '</option>';
		if ($has_preselected) { $body = ''; }
			foreach ($data as $key => $value) {
				if (contains_array($value)) {
					$arr_key = key($value);
					$body .= "<optgroup label='{$arr_key}'>";
					if (isset($value[$arr_key])) {
						$rows = $value[$arr_key];
						foreach ($rows as $index => $row) {
							$body .= '<option value="'. $row[$id_key] .'" '. ($id_selected == $row[$id_key] ? 'selected' : '') .'>'. $row[$name_key] .'</option>';
						}
					}
					
					$body .= "</optgroun>";
				} else {
					$body .= '<option value="'. $value[$id_key] .'" '. ($id_selected == $value[$id_key] ? 'selected' : '') .'>'. $value[$name_key] .'</option>';
				}
			}

      	$last = '</select>';

      	return $first . $body . $last;
	}

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

	public function generate_sidebar_items($menu_items) {
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

	public function generate_sidebar_sub_menu($main_item) {
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

	public function get_pagination_offset($page = 1, $limit = 10, $num_rows = 10) {
		$page 	= ($page < 1 ? 1 : $page);
		$offset = ($page - 1) * $limit;
		$offset = ($offset >= $num_rows && $page == 1 ? 0 : $offset);
		return $offset;
	}

	function table_listing( $user_type = '' , $result = array(), $total_rows = 0, $page = 0, $limit = 10, $actions = array(), $uri_segment = 3, $prev_next_only = false, $has_page_count = false, $prefix_id = '', $extra_url ='', $custom_url = '') {

		$this->load->library('table');
		$this->load->library('pagination');

		if(empty($result) ){
			$results['table'] = '<div class="col-xs-12"><div class="row"><div class="col-md-12"><div class="alert alert-warning alert-dismissible" role="alert"> No Record Found ! &nbsp&nbsp<a href="javascript:history.go(-1)" class="btn btn-default" role="button">Back</a></div></div></div></div>';
			$results['pagination'] = '';
		}else{

			// $page = $offset;

			if( $user_type == 'member' ){
				// member url
				$url = member_url();
			}else if( $user_type == 'user' ){
				// user url
				$url = user_url();
			}else if( $user_type == 'admin' ){
				// admin url
				$url = admin_url();
			}else{
				// base url
				//$url = public_url();
				$url = base_url();
			}

			$url =  site_url( $user_type . DIR_SEPARATOR . create_url_title(strtolower(get_controller()), "dash") . DIR_SEPARATOR ) . ( $extra_url != '' ? $extra_url . DIR_SEPARATOR: '' );
			$url = strtolower($url);

			// set table template
			$tmpl = array ( 
				'table_open'  => '<div class="table-responsive"><table class="table table-dark table-bordered">', 
				'table_close' => '</table></div><br/>' 
			);

			$this->table->set_template($tmpl);
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			// set pagination template
		  	$config["base_url"] = $url; //site_url('');
		  	$config['first_url'] = $config['base_url']. ($_GET ? '?'.http_build_query($_GET) : ''  );
		    $config["total_rows"] = $total_rows;
			$config["per_page"] = $limit;
			
			$num_pages = ceil($config["total_rows"] / $config["per_page"]);
			$num_pages = round($num_pages);
			$config["num_links"] = $num_pages;

			$state = ($prev_next_only ? "hidden" : "");

		    // default pagination set up
			$config["uri_segment"] = $uri_segment;
			
			$config['full_tag_open'] = '<div class="row"><div class="col-lg-11"><nav aria-label="pagination"><ul class="pagination">';
			$config['full_tag_close'] = '</ul></nav></div></div>';	 

			$config['prev_link']		= 'Prev';
			$config['prev_tag_open'] 	= '<li class="page-item">';
			$config['prev_tag_close'] 	= "</li>" . ($page == $num_pages ? '<li class="page-item disabled"><a class="page-link force-last" rel="prev" href="#">Next</a></li>' : '');

			$config['next_link']		= 'Next';
			$config['next_tag_open'] 	= ($page == 1 ? '<li class="page-item disabled"><a class="page-link force-first" rel="prev" href="#">Prev</a></li>' : '') . '<li class="page-item">';
			$config['next_tag_close'] 	= "</li>";

			$config['num_tag_open'] = '<li class="page-item '. $state .'">';
			$config['num_tag_close'] = '</li>';

			$config['cur_tag_open'] = '<li class="page-item active '. $state .'"><a class="page-link" href="#">';
			$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a>';

			$config['first_tag_open'] = '<li class="page-item '. $state .'">';
			$config['first_tag_close'] = "</li>";

			$config['last_tag_open'] = '<li class="page-item '. $state .'">';
			$config['last_tag_close'] = "</li>";
			
			$config['attributes'] = array('class' => 'page-link');
			$config['use_page_numbers'] = TRUE;
			$config['display_pages'] = TRUE;

		    $this->pagination->initialize($config);

		    $header = array();

		    if(count($result) != 0 ){
		    	$resultKey = $result[0];
	    	
		    	if(array_key_exists('id', $resultKey)){
		    		unset($resultKey['id']);
		    	}
		    	if(array_key_exists('reported', $resultKey)){
		    		unset($resultKey['reported']);
		    	}
		    	if(array_key_exists('xstatus', $resultKey)){
		    		unset($resultKey['xstatus']);
		    	}

		    	$table_header = array_keys($resultKey);
		    	$keys = array_keys($result[0]);
		    }

		    if(!empty($actions)){
		    	$table_header = array_merge($table_header, array('Action'));
		    }

		    $rows = array();
		   
			$this->table->set_heading($table_header);
			
			foreach ($result as $row) {
				$rows = array();
				$links = '';
				$isID = false;
				$id = '';
				$reportedStatus = 0;
				$readyToDelete = false;
				$hasStatus = false;
				$hasReported = false;
				foreach ($keys as $key ) {
					# code...

					if(strtolower($key) == 'status'){
						$rows[] = ($row[$key] == 0 ? 'Inactive' : 'Active' );
						$hasStatus = true;
					}elseif($key == 'id'){
						$isID = true;
						$id = $row[$key];
					}elseif(strtolower($key) == 'reported'){
						$hasReported = true;
						$reportedStatus = $row[$key];
					}elseif( strtolower($key) == 'xstatus' ){
						if($row['xstatus'] == 1 && $row['reported'] == 0){
							$readyToDelete = true;
						}
					}else{
						$rows[] = $row[$key];
					}
				}
				
				if(!empty($actions)){
					$action_count = count($actions);
					$col_size = 12 / $action_count;

					$links = "<div class='row'>";

					foreach($actions as $action){
						if($action == 'edit'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". $url  . 'edit/'.$id."?last_page=". $page ."' class='btn btn-block btn-success' title='Edit' role='button'><span class='mdi mdi-table-edit'></span></a></div></div>";
						} else if($action == 'view'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". $url  . 'view/'.$id."' class='btn btn-block btn-primary' title='View' role='button'><span class='glyphicon glyphicon-eye-open'></span></a></div></div>";
						} else if($action == 'read'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". ($custom_url != "" ? $custom_url : $url)  . 'message/'.$id."' class='btn btn-block btn-success' title='Read' role='button'><span class='glyphicon glyphicon-envelope'></span></a></div></div>";
						} else if($action == 'reset'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". ($custom_url != "" ? $custom_url : $url)  . 'reset/'.$id."' class='btn btn-block btn-success' title='Reset Code' role='button'><span class='fa fa-undo'></span></a></div></div>";
						} else if($action == 'deactivate'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". ($custom_url != "" ? $custom_url : $url)  . 'reset/'.$id."' class='btn btn-block btn-success title='Reset Code' role='button'><span class='fa fa-lock'></span></a></div></div>";
						} else if($action == 'delete'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". ($custom_url != "" ? $custom_url : $url)  . 'delete/'.$id."' class='btn btn-block btn-danger' title='Delete' role='button'>Delete</a></div></div>";
						} else if($action == 'attendance'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". ($custom_url != "" ? $custom_url : $url)  . 'add/'.$id."' class='btn btn-block btn-success' title='Delete' role='button'><span class='fa fa-clock-o'></span></a></div></div>";
						} else if($action == 'timesheet'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". ($custom_url != "" ? $custom_url : $url)  . 'view/'.$id."' class='btn btn-block btn-primary' title='View' role='button'><span class='fa fa-table'></span></a></div></div>";
						} else if($action == 'update'){
							$links .= "<div class='col-lg-{$col_size}'><div class='form-group'><a href='". ($custom_url != "" ? $custom_url : $url)  . 'update/'.$id."' class='btn btn-block btn-success btn-primary' title='Update' role='button'><span class='mdi mdi-table-edit'></span></a></div></div>";
						}
					}

					$links .= "</div>";
					$rows[] = $links;
		    	}

				$this->table->add_row($rows);
			}

			$this->_data['pages'] = "";

			if ($has_page_count) {
				$pages = [];
				for ($i = 1;$i <= $num_pages; $i++) {
					$pages[] = array('page_id' => $i);
				}
	
				$this->_data['pages']	=	$this->generate_selection("pages", $pages, $page, "page_id", "page_id", true);
				$this->_data['pages'] = '<div class="row"><div class="col-lg-1">'. $this->_data['pages'] .'</div><div class="col-lg-2" style="line-height: 2.2;">'. " out of " . $num_pages .' page(s)</div></div><br/>';
			}

			$results['table'] = $this->table->generate();
			$results['pagination'] = $this->_data['pages']  . $this->pagination->create_links();
		}
		
		return $results;
	}
}