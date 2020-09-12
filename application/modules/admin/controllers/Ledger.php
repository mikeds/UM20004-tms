<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends Admin_Controller {
	private
		$_account_data = NULL;
		
	public function after_init() {
		$this->set_scripts_and_styles();
		
		$this->load->model("admin/ledger_data_model", "ledger");

		$this->_account_data = $this->get_account_data();
	}

	public function index() {
		$account_results            = $this->_account_data['results'];
		$merchant_oauth_bridge_id	= $account_results['merchant_oauth_bridge_id'];
		
		$select = array(
			'transaction_id as "TX ID"',
			'transaction_sender_ref_id as "Sender Ref ID"',
			'transaction_type_name as "TX Type"',
			'transaction_requested_by as "Requested By"',
			'FORMAT(transaction_amount, 2) as "TX Amount"',
			'FORMAT(transaction_fee, 2) as "Fee"',
			'FORMAT(ledger_datum_old_balance, 2) as "Old Balance"',
			'FORMAT(ledger_datum_amount, 2) as "Debit/Credit Amount"',
			'FORMAT(ledger_datum_new_balance, 2) as "New Balance"',
			'ledger_datum_date_added as "Date Added"'
		);

		$where = array(
			'ledger_datum_bridge_id'	=> $merchant_oauth_bridge_id
		);

		$inner_joints = array(
			array(
				'table_name'	=> 'transactions',
				'condition'		=> 'transactions.transaction_id = ledger_data.tx_id'
			),
			array(
				'table_name'	=> 'transaction_types',
				'condition'		=> 'transaction_types.transaction_type_id = transactions.transaction_type_id'
			)
		);

		$data = $this->ledger->get_data(
			$select,
			$where,
			array(),
			$inner_joints,
			array(
				'filter'	=> 'ledger_datum_date_added',
				'sort'		=> 'ASC'
			)
		);

		$ledger_data = $this->filter_ledger($data);

		$this->_data['listing'] = $this->table_listing('', $ledger_data);
		$this->_data['title']  = "Ledger";
		$this->set_template("ledger/list", $this->_data);
	}
}
