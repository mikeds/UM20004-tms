<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {
	private
		$_account_data = NULL;
		
	public function after_init() {
		$this->set_scripts_and_styles();

		$this->_account_data = $this->get_account_data();
	}

	private function get_wallet_balance() {
        $account_results            = $this->_account_data['results'];
        $admin_oauth_bridge_id		= $account_results['admin_oauth_bridge_id'];
        $account_oauth_bridge_id    = $account_results['oauth_bridge_id'];
        $wallet_address             = $account_results['wallet_address'];

		$wallet_data 				= $this->get_wallet_data($wallet_address);
		$wallet_data_results    	= $wallet_data['results'];
		$balance 					= $wallet_data_results['balance'];
		return $balance;
	}

	public function index() {
		$balance	= number_format($this->get_wallet_balance(), 2, ".", ",");

		$this->_data['balance'] 	= $balance;
		$this->_data['title']  		= "Dashboard";
		$this->set_template("dashboard/index", $this->_data);
	}
}
