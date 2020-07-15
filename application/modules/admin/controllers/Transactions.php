<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends Admin_Controller {
	public function after_init() {
		$this->set_scripts_and_styles();
		$this->load->model('admin/transactions_model', 'transactions');
	}

	public function cash_in($page = 1) {
		$wallet_row = $this->_user_data['wallet_row'];

		$select = array(
			array(
				'transaction_id as id',
				'IF(transaction_status = 1, "APPROVED", IF(transaction_date_expiration < "'. $this->_today .'" OR transaction_status = 2, "CANCELLED", "PENDING")) as "Transaction Status"',
				'UCASE(transaction_number) as "Transaction Number"',
				'FORMAT(transaction_amount, 2) as "Amount"',
				'FORMAT(transaction_fees, 2) as "Fees"',
				'DATE_FORMAT(transaction_date_created, "%a, %b. %e. %Y %r") as "Transaction Date"',
				'DATE_FORMAT(transaction_date_expiration, "%a, %b. %e. %Y %r") as "Expiration Date"',
			)
		);

		$where = array(
			'transaction_from_wallet_address' => $wallet_row->wallet_address
		);

		$total_rows = $this->transactions->get_count(
			$where
		);
		$offset = $this->get_pagination_offset($page, $this->_limit, $total_rows);
	    $results = $this->transactions->get_data($select, $where, array(), array('filter'=>'transaction_id', 'sort'=>'DESC'), $offset, $this->_limit);

		$this->_data['listing'] = $this->table_listing('', $results, $total_rows, $offset, $this->_limit, array(), 3, false, '', '');
		$this->_data['title']  = "Cash In - Listing";
		$this->set_template("transactions/list", $this->_data);
	}

	public function cash_out($page = 1) {
		$wallet_row = $this->_user_data['wallet_row'];

		$select = array(
			array(
				'transaction_id as id',
				'IF(transaction_status = 1, "APPROVED", IF(transaction_date_expiration < "'. $this->_today .'" OR transaction_status = 2, "CANCELLED", "PENDING")) as "Transaction Status"',
				'UCASE(transaction_number) as "Transaction Number"',
				'FORMAT(transaction_amount, 2) as "Amount"',
				'FORMAT(transaction_fees, 2) as "Fees"',
				'DATE_FORMAT(transaction_date_created, "%a, %b. %e. %Y %r") as "Transaction Date"',
				'DATE_FORMAT(transaction_date_expiration, "%a, %b. %e. %Y %r") as "Expiration Date"',
			)
		);

		$where = array(
			'transaction_to_wallet_address' => $wallet_row->wallet_address
		);

		$total_rows = $this->transactions->get_count(
			$where
		);
		$offset = $this->get_pagination_offset($page, $this->_limit, $total_rows);
	    $results = $this->transactions->get_data($select, $where, array(), array('filter'=>'transaction_id', 'sort'=>'DESC'), $offset, $this->_limit);

		$this->_data['listing'] = $this->table_listing('', $results, $total_rows, $offset, $this->_limit, array(), 3, false, '', '');
		$this->_data['title']  = "Cash Out - Listing";
		$this->set_template("transactions/list", $this->_data);
	}
}
