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
		$_limit = 50;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize all configs, helpers, libraries from parent
		parent::__construct();
		date_default_timezone_set("Asia/Manila");
		$this->_today = date("Y-m-d H:i:s");

		$this->validate_login();
		$this->setup_nav_sidebar_menu();
		$this->after_init();
	}

	public function new_ledger_datum($description = "", $transaction_id, $from_wallet_address, $to_wallet_address, $balances) {
		$this->load->model("admin/ledger_data_model", "ledger");
		$this->load->model("admin/wallet_addresses_model", "wallet_addresses");

		$to_oauth_bridge_id 	= getenv("SYSADD");
		$from_oauth_bridge_id 	= getenv("SYSADD");


		$from_row = $this->wallet_addresses->get_datum(
			'',
			array(
				'wallet_address' => $from_wallet_address
			)
		)->row();

		if ($from_row != "") {
			$from_oauth_bridge_id 	= $from_row->oauth_bridge_id;
		}

		$to_row = $this->wallet_addresses->get_datum(
			'',
			array(
				'wallet_address' => $to_wallet_address
			)
		)->row();

		if ($to_row != "") {
			$to_oauth_bridge_id 	= $to_row->oauth_bridge_id;
		}

		$old_balance = $balances['old_balance'];
		$new_balance = $balances['new_balance'];
		$amount		 = $balances['amount'];

		$ledger_type = 0; // unknown

		if ($amount < 0) {
			$ledger_type = 1; // debit
		} else if ($amount >= 0) {
			$ledger_type = 2; // credit
		}

		// add new ledger data
		$ledger_data = array(
			'tx_id'                         => $transaction_id,
			'ledger_datum_type'				=> $ledger_type,
			'ledger_datum_bridge_id'		=> $to_oauth_bridge_id,
			'ledger_datum_desc'             => $description,
			'ledger_from_wallet_address'    => $from_wallet_address,
			'ledger_to_wallet_address'      => $to_wallet_address,
			'ledger_from_oauth_bridge_id'   => $from_oauth_bridge_id,
			'ledger_to_oauth_bridge_id'     => $to_oauth_bridge_id,
			'ledger_datum_old_balance'      => $old_balance,
			'ledger_datum_new_balance'      => $new_balance,
			'ledger_datum_amount'           => $amount,
			'ledger_datum_date_added'       => $this->_today
		);

		$ledger_datum_id = $this->generate_code(
			$ledger_data,
			"crc32"
		);

		$ledger_data = array_merge(
			$ledger_data,
			array(
				'ledger_datum_id'   => $ledger_datum_id,
			)
		);

		$ledger_datum_checking_data = $this->generate_code($ledger_data);

		$this->ledger->insert(
			array_merge(
				$ledger_data,
				array(
					'ledger_datum_checking_data' => $ledger_datum_checking_data
				)
			)
		);
	}

	public function update_wallet($wallet_address, $amount) {
		$this->load->model("admin/wallet_addresses_model", "wallet_addresses");

		$row = $this->wallet_addresses->get_datum(
			'',
			array(
				'wallet_address'	=> $wallet_address
			)
		)->row();

		if ($row == "") {
			return false;
		}

		$wallet_balance         = $this->decrypt_wallet_balance($row->wallet_balance);

		$old_balance            = $wallet_balance;
		$encryted_old_balance   = $this->encrypt_wallet_balance($old_balance);

		$new_balance            = $old_balance + $amount;
		$encryted_new_balance   = $this->encrypt_wallet_balance($new_balance);

		$wallet_data = array(
			'wallet_balance'                => $encryted_new_balance,
			'wallet_address_date_updated'   => $this->_today
		);

		// update wallet balances
		$this->wallet_addresses->update(
			$wallet_address,
			$wallet_data
		);

		return array(
			'old_balance'	=> $old_balance,
			'new_balance'	=> $new_balance,
			'amount'		=> $amount
		);
	}

	public function decrypt_wallet_balance($encrypted_balance) {
		return openssl_decrypt($encrypted_balance, $this->_ssl_method, getenv("BPKEY"));
	}

	public function filter_ledger($data) {
		$results = array();

		foreach ($data as $datum) {
			$row = $this->get_oauth_account_info($datum['Requested By']);

			$tmp_result = $datum;

			if ($row) {
				$tmp_result['Requested By'] = trim($row['account_fname'] . " ". $row['account_lname'] . " " . $row['account_lname']);
			} else {
				$tmp_result['Requested By'] = "SYSTEM";
			}

			$results[] = $tmp_result;
		}

		return $results;
	}

	public function get_oauth_account_info($oauth_bridge_id) {
		$this->load->model("admin/accounts_model", "accounts");
		$this->load->model("admin/tms_admin_accounts_model", "tms_admin_accounts");
		$this->load->model("admin/merchant_accounts_model", "merchant_accounts");
		$this->load->model("admin/client_accounts_model", "client_accounts");
			
		$where = array(
			'oauth_bridge_id'	=> $oauth_bridge_id
		);

		$row_account = $this->accounts->get_datum(
			'',
			$where
		)->row();

		if ($row_account != "") {
			return array(
				'account_number'	=> $row_account->account_number,
				'account_fname'		=> $row_account->account_fname,
				'account_mname'		=> $row_account->account_mname,
				'account_lname'		=> $row_account->account_lname, 
			);
		}

		$row_admin = $this->tms_admin_accounts->get_datum(
			'',
			$where
		)->row();

		if ($row_admin != "") {
			return array(
				'account_number'	=> $row_admin->account_number,
				'account_fname'		=> $row_admin->account_fname,
				'account_mname'		=> $row_admin->account_mname,
				'account_lname'		=> $row_admin->account_lname, 
			);
		}

		$row_merchant = $this->merchant_accounts->get_datum(
			'',
			$where
		)->row();

		if ($row_merchant != "") {
			return array(
				'account_number'	=> $row_merchant->account_number,
				'account_fname'		=> $row_merchant->account_fname,
				'account_mname'		=> $row_merchant->account_mname,
				'account_lname'		=> $row_merchant->account_lname, 
			);
		}

		$row_client = $this->client_accounts->get_datum(
			'',
			$where
		)->row();

		if ($row_client != "") {
			return array(
				'account_number'	=> $row_client->account_number,
				'account_fname'		=> $row_client->account_fname,
				'account_mname'		=> $row_client->account_mname,
				'account_lname'		=> $row_client->account_lname, 
			);
		}

		return false;
	}

	public function get_account_data() {
		$this->load->model('admin/merchant_accounts_model', 'accounts');
		$this->load->model('admin/wallet_addresses_model', 'wallet_addresses');

		$account = $this->session->userdata("{$this->_base_session}");

		if (!isset($account['id'])) {
			redirect(base_url() . "logout");
		}

		$id = $account['id'];

		/*
			- wallet address
			- oauth bridge id
			- secret id
			- secret key
		*/

		$row = $this->accounts->get_datum(
			'',
			array(
				'account_number' => $id
			),
			array(),
			array(
				array(
					'table_name' 	=> 'oauth_bridges',
					'condition'		=> 'oauth_bridges.oauth_bridge_id = merchant_accounts.oauth_bridge_id'
				),
				array(
					'table_name' 	=> 'merchants',
					'condition'		=> 'merchants.merchant_number = merchant_accounts.merchant_number'
				)
			),
			array(
				'*',
				'merchants.oauth_bridge_id as merchant_oauth_bridge_id'
			)
		)->row();
		
		if ($row == "") {
			return array(
				'status' 	=> false,
				'message' 	=> 'Cannot find account data!'
			);
		}

		$oauth_bridge_id 			= $row->oauth_bridge_id;
		$merchant_oauth_bridge_id	= $row->merchant_oauth_bridge_id;
		$admin_oauth_bridge_id		= $row->oauth_bridge_parent_id;

		$wallet_address = $this->get_wallet_address($merchant_oauth_bridge_id);

		return array(
			'status' => true,
			'results' => array(
				'wallet_address' 			=> $wallet_address,
				'oauth_bridge_id'			=> $oauth_bridge_id,
				'merchant_oauth_bridge_id'	=> $merchant_oauth_bridge_id,
				'admin_oauth_bridge_id'		=> $admin_oauth_bridge_id,
				'secret_id'					=> '',
				'secret_key'				=> '',
			)
		);
	}

	public function get_wallet_address($bridge_id) {
		$this->load->model('admin/wallet_addresses_model', 'wallet_addresses');

		$row = $this->wallet_addresses->get_datum(
			'',
			array(
				'oauth_bridge_id' => $bridge_id
			)
		)->row();

		if ($row == "") {
			return "";
		}

		return $row->wallet_address;
	}

	public function get_wallet_data($wallet_address) {
		$row = $this->wallet_addresses->get_datum(
			'',
			array(
				'wallet_address' => $wallet_address
			)
		)->row();

		if ($row == "") {
			return array(
				'status' => false,
				'message' => 'Cannot find wallet address'
			);
		}

		/*
			- balance
			- hold balance
			- last updated
		*/

		$encrypted_balance 		= $row->wallet_balance;
		$encrypted_hold_balance = $row->wallet_hold_balance;

		$balance 		= openssl_decrypt($encrypted_balance, $this->_ssl_method, getenv("BPKEY"));
		$hold_balance 	= openssl_decrypt($encrypted_hold_balance, $this->_ssl_method, getenv("BPKEY"));

		return array(
			'status' => true,
			'results' => array(
				'balance' 		=> $balance,
				'hold_balance'	=> $hold_balance
			)
		);
	}

	public function encrypt_wallet_balance($balance) {
		return openssl_encrypt($balance, $this->_ssl_method, getenv("BPKEY"));
	}

	public function generate_code($data, $hash = "sha256") {
		$json = json_encode($data);
		return hash_hmac($hash, $json, getenv("SYSKEY"));
	}

	public function create_wallet_address($account_number, $bridge_id, $oauth_bridge_parent_id) {
		$this->load->model('admin/wallet_addresses_model', 'wallet_addresses');

		// add address
		$wallet_address = $this->generate_code(
			array(
				'account_number' 				=> $account_number,
				'oauth_bridge_id'				=> $bridge_id,
				'wallet_address_date_created'	=> $this->_today,
				'admin_oauth_bridge_id'			=> $oauth_bridge_parent_id
			)
		); 

		// create wallet address
		$this->wallet_addresses->insert(
			array(
				'wallet_address' 				=> $wallet_address,
				'wallet_balance'				=> openssl_encrypt(0, $this->_ssl_method, getenv("BPKEY")),
				'wallet_hold_balance'			=> openssl_encrypt(0, $this->_ssl_method, getenv("BPKEY")),
				'oauth_bridge_id'				=> $bridge_id,
				'wallet_address_date_created'	=> $this->_today
			)
		);
	}

	public function create_token_auth($account_number, $bridge_id) {
		$this->load->model('admin/oauth_clients_model', 'oauth_clients');

		// create api token
		$this->oauth_clients->insert(
			array(
				'client_id' 		=> $bridge_id,
				'client_secret'		=> $this->generate_code(
					array(
						'account_number'	=> $account_number,
						'date_added'		=> $this->_today,
						'oauth_bridge_id'	=> $bridge_id
					)
				),
				'oauth_bridge_id'	=> $bridge_id,
				'client_date_added'	=> $this->_today
			)
		);
	}

	public function validate_username($type, $username, $id = "") {
		$flag = false;

		$this->load->model('admin/accounts_model', 'accounts');
		$this->load->model('admin/tms_admin_accounts_model', 'admin_accounts');
		$this->load->model('admin/merchant_accounts_model', 'merchant_accounts');

		$account_row = $this->accounts->get_datum(
			'',
			array(
				'account_username' => $username
			)
		)->row();

		if ($account_row != "") {
			$acc_id = $account_row->account_number;

			if ($type == "admin" && $id != "") {
				if ($acc_id == $id) {
					$flag = false;
				} else {
					$flag = true;
					goto end;
				}
			} else {
				$flag = true;
				goto end;
			}
		}

		$tms_admin_account_row = $this->admin_accounts->get_datum(
			'',
			array(
				'account_username' => $username
			)
		)->row();

		if ($tms_admin_account_row != "") {
			$acc_id = $tms_admin_account_row->account_number;

			if ($type == "tms_admin" && $id != "") {
				if ($acc_id == $id) {
					$flag = false;
				} else {
					$flag = true;
					goto end;
				}
			} else {
				$flag = true;
				goto end;
			}
		}

		$merchant_account_row = $this->merchant_accounts->get_datum(
			'',
			array(
				'account_username' => $username
			)
		)->row();

		if ($merchant_account_row != "") {
			$acc_id = $merchant_account_row->account_number;

			if ($type == "merchant" && $id != "") {
				if ($acc_id == $id) {
					$flag = false;
				} else {
					$flag = true;
					goto end;
				}
			} else {
				$flag = true;
				goto end;
			}
		}

		end:

		return $flag;
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

	// private function set_user_data() {
	// 	$this->_user_data = $this->get_user();
	// 	is_null($this->_user_data) ? redirect(base_url() . "logout") : "";
	// }

	public function get_transaction_info($data) {
		$results = array();
		
		foreach ($data as $key => $datum) {
			// $wallet_address	= $datum['transaction_requested_by']; 
			// $wallet_data 	= $this->get_wallet_data($wallet_address);

			$transaction_number = $datum["transaction_number"];
			$image_url =  base_url() . "transactions/qr-code/" . $transaction_number;
			$qr_code_image = "<img src='{$image_url}' class='img-thumbnail'>";

			$new_datum = array(
				"id" => $datum["id"],
				'QR Code' => $qr_code_image, 
				"Transaction Status" => $datum["transaction_status"],
				"Transaction Number" => $transaction_number,
				// "Requested by" => $request_info,
				"Amount" => $datum["amount"],
				"Fees" => $datum["fees"],
				"Transaction Date" => $datum["transaction_date_created"],
				"Approved Date" => $datum["transaction_date_approved"],
				"Expiration Date" => $datum["transaction_date_expiration"],
			);

			$results[] = $new_datum;
		}

		return $results;
	}

	public function generate_image_gallery($images_data) {
		$content = "";
		
		foreach ($images_data as $key => $image_datum) {
			$index = $key + 1;
			$id = $image_datum['id'];
			$base64_image = $image_datum['base64_image'];
			$confirmation_delete_url = base_url() . "marketplace/products/confirmation-remove/image-{$id}";
$content .= <<<HTML
			<tr>
				<th scope="row">$index</th>
				<td><img src="data:image/png;base64,{$base64_image}" class="img-thumbnail"></td>
				<td>
					<div class="row">
						<div class="col-xl-12">
							<div class="form-group">
								<a href="{$confirmation_delete_url}" class="btn btn-block btn-warning" title="Update" role="button">
									<span class="mdi mdi-delete">REMOVE</span>
								</a>
							</div>
						</div>
					</div>
				</td>
			</tr>
HTML;
		}

$HTML = <<<HTML
			<table class="table table-dark">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Image</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody class="image-gallery">
					$content
				</tbody>
			</table>
HTML;

		return $HTML;
	}

	public function upload_files($files, $title, $file_size_limit = 20, $allowed_types = "jpg|jpeg|JPG|JPEG|PNG|png") {
		$upload_path = "{$this->_upload_path}/images";
        $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => $allowed_types,
            'overwrite'     => 1,                       
        );

        $this->load->library('upload', $config);

        $items = array();

		$error_images = array();

		// validate first the file size 20M limit per image
		foreach ($files['name'] as $key => $file) {
			$file_size = $files['size'][$key];

			if ($file_size > ($file_size_limit * MB)) {
				$error_images[] = $files['name'][$key];
			}
		}

		if (!empty($error_images)) {
			return array(
				'error' => true,
				'error_message' => "Image(s) is/are exceeded 20MB size.",
				'error_images' => $error_images,
			);
		}

		$error_upload = array();
		$data = array();

        foreach ($files['name'] as $key => $file) {
            $_FILES['files[]']['name']= $files['name'][$key];
            $_FILES['files[]']['type']= $files['type'][$key];
            $_FILES['files[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['files[]']['error']= $files['error'][$key];
            $_FILES['files[]']['size']= $files['size'][$key];
			

			$ext = explode(".", $file);
			$ext = isset($ext[count($ext) - 1]) ? $ext[count($ext) - 1] : ""; 

			$today = strtotime($this->_today);
			$image_id = "{$title}_{$key}_{$today}";
            $file_name =  "{$image_id}.{$ext}";

            $items[] = $file_name;

            $config['file_name'] = $file_name;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('files[]')) {
				$this->upload->data();

				// get file uploaded
				$full_path 		= "{$upload_path}/{$file_name}";
				$filecontent 	= file_get_contents($full_path);

				// update image save base64
				$data[] = array(
					'image_id' => $image_id,
					'base64_image' => rtrim(base64_encode($filecontent))
				);

				// delete uploaded image
				if(file_exists($full_path)){
					unlink($full_path);
				}
            } else {
				$error_upload[] = array(
					'error_image' => $files['name'][$key],
					'error_message' => $this->upload->display_errors()
				);
            }
        }

		return empty($error_upload) ? 
			array(
				'results' => $data
			): 
			array(
				'error' => true,
				'error_data' => $error_upload
			);
    }

	public function resize_image($image_source, $new_width = 2048) {
		$this->load->library('image_lib');

		$image_resize_config =  array(
			'image_library'   => 'gd2',
			'source_image'    =>  $image_source,
			'maintain_ratio'  =>  true,
			'width'           =>  $new_width,
			// 'height'          =>  300,
		);

		$this->image_lib->clear();
		$this->image_lib->initialize($image_resize_config);
		$this->image_lib->resize();
		return file_get_contents($image_source);
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
			'menu_id'			=> 'ledger',
			'menu_title'		=> 'Ledger',
			'menu_url'			=> 	base_url() . "ledger",
			'menu_controller'	=> 'ledger',
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
