<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet_addresses_model extends CI_Model {
	private 
		$_table	= 'wallet_addresses  wallet_addresses',
		$_table_x	= 'wallet_addresses';

	private
		$_id = "wallet_address";

	function get_datum($id = '', $data = array(), $where_or = array()) {
		$this->db->from( $this->_table_x );

		if( !empty($data) ){
			$this->db->where( $data );
		}

		if( !empty($where_or) ){
			$this->db->or_where($where_or);
		}

		if( !empty($id) ){
			$this->db->where_not_in($this->_id, $id);
		}

		$query = $this->db->get();

		return $query;
	}

	function get_data( $select = array('*'), $data = array(), $like= array(), $order_by = array(), $offset = 0, $limit = 0, $group_by = '' ) {
		
		$this->db->select(ARRtoSTR($select),false);

		$this->db->from( $this->_table );
		
		if(!empty($data)){
			$this->db->where($data);
		}

		if(!empty( $like )){
		$this->db->like( $like['field'], $like['value'] );
		}

		if(!empty($limit)){
			$this->db->limit($limit, $offset);
		}
		
		if( !empty( $order_by ) ) {
			$this->db->order_by( $order_by['filter'],$order_by['sort'] );
		}

		if( $group_by != '' ) {
			$this->db->group_by( $group_by );
		}

		$query = $this->db->get();

		$results = $query->result_array();

		return $results;

	}

	function get_count( $data = array(), $like = array(), $order_by = array(), $offset = 0, $count = 0 ) {
		if( !empty($data) ){
			
			$this->db->from($this->_table);

			if( !empty( $data ) ) {
				$this->db->where( $data );
			}

			if(!empty( $like )){
			$this->db->like( $like['field'], $like['value'] );
			}   

			if( !empty( $count ) ) {
				$this->db->limit( $count, $offset );
			}
			
			if( !empty( $order_by ) ) {
				$this->db->order_by( $order_by['filter'],$order_by['sort'] );
			}
								
			return $this->db->count_all_results();
		}else{
			return $this->db->count_all($this->_table_x);
		}
	}

	function insert( $data ) {
		$this->db->insert( $this->_table_x , $data);
		return $this->db->insert_id();
	}

	function update( $id = 0 , $data = array() ){

		if( !empty($data) && !empty($id) ){

			$this->db->where($this->_id, $id);
			$this->db->update( $this->_table_x, $data); 
			return $this->db->affected_rows();
		}else{
			return false;
		}
	} 

	/*
	public function delete($id){
		$this->db->where($this->_id, $id); 
		$this->db->delete($this->_table_x);
	}
	*/
}

