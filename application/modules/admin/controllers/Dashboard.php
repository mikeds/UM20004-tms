<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function after_init() {
		$this->set_scripts_and_styles();
		$this->load->model('admin/merchants_model', 'merchants');
		$this->load->model('admin/wallets_model', 'wallets');
	}

	public function index() {
		$this->_data['title']  = "Dashboard";
		$merchant_row = $this->_user_data['merchant_row'];

		$bridge_id = $merchant_row->oauth_client_bridge_id;
		
		$wallet_row = $this->wallets->get_datum(
			'',
			array(
				'oauth_client_bridge_id' => $bridge_id
			)
		)->row();

		if ($wallet_row == "") {
			redirect(base_url() . "logout");
		}

		$balance = $wallet_row->wallet_balance;
		$balance = number_format($balance, 2);
		$currency_symbol = "P";

		$this->_data['balance'] = "{$currency_symbol} {$balance}";

		$this->set_template("dashboard/index", $this->_data);
	}
}
