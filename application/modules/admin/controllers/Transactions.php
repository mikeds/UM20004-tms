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
				'transaction_requested_by',
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
		$results = $this->get_transaction_info($results);

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
				'transaction_requested_by',
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
		$results = $this->get_transaction_info($results);

		$this->_data['listing'] = $this->table_listing('', $results, $total_rows, $offset, $this->_limit, array(), 3, false, '', '');
		$this->_data['title']  = "Cash Out - Listing";
		$this->set_template("transactions/list", $this->_data);
	}

	/*
		$type:
		1 = cash_in // merchant to client
		2 = cash_out // client to merchant
		3 = send_to // client to client
		4 = topup // tmsadmin to merchant
		5 = withdraw // merchant to tmsadmin
	*/
	private function get_transaction_info($data, $type = 1) {
		$results = array();
		
		foreach ($data as $key => $datum) {
			$wallet_info = $this->get_wallet_info($datum["transaction_requested_by"]);

			$request_info = "";

			if ($wallet_info) {
				$request_info = "{$wallet_info['name']}<br>{$wallet_info['email_address']}<br>{$wallet_info['mobile_no']}";
			}

			$transaction_number = $datum["Transaction Number"];
			$image_url =  base_url() . "transactions/qr-code/" . $transaction_number;
			$qr_code_image = "<img src='{$image_url}' class='img-thumbnail'>";

			$new_datum = array(
				"id" => $datum["id"],
				'QR Code' => $qr_code_image, 
				"Transaction Status" => $datum["Transaction Status"],
				"Transaction Number" => $transaction_number,
				"Requested by" => $request_info,
				"Amount" => $datum["Amount"],
				"Fees" => $datum["Fees"],
				"Transaction Date" => $datum["Transaction Date"],
				"Expiration Date" => $datum["Expiration Date"],
			);

			$results[] = $new_datum;
		}

		return $results;
	}
}
