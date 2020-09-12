<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Public_Controller {

	public function after_init(){
		$this->set_scripts_and_styles();
		$this->load->model('admin/merchant_accounts_model', 'merchant_accounts');
	}

	public function index() {
		$this->_data['form_url']		= base_url() . "login";
		$this->_data['notification'] 	= $this->session->flashdata('notification');

		if ($_POST) {
			if ($this->form_validation->run('login')) {
				$username = $this->input->post("username");
				$password = $this->input->post("password");
				$password = hash("sha256", $password);

				$inner_joints = array(
					array(
						'table_name' 	=> 'oauth_bridges',
						'condition'		=> 'oauth_bridges.oauth_bridge_id = merchant_accounts.oauth_bridge_id'
					)
				);

				$row = $this->merchant_accounts->get_datum(
					'',
					array(
						'account_username'	=> $username,
						'account_password'	=> $password,
						'account_status' 	=> 1
					),
					array(),
					$inner_joints
				)->row();
				
				if ($row == "") {
					// Invalid username or password
					$this->session->set_flashdata('notification', $this->generate_notification('danger', 'The username or password is/are incorrect!'));
					redirect($this->_data['form_url']);
				}

				$session[$this->_base_session] = array(
					'id'			=> $row->account_number,
					'username'		=> $row->account_username,
					'fname'			=> $row->account_fname,
					'mname'			=> $row->account_mname,
					'lname'			=> $row->account_lname,
					'avatar_base64'	=> $this->generate_avatar($row->account_avatar_base64)
				);

				$this->session->set_userdata($session);

				redirect(base_url());
			}
		}

		$this->set_template(
			"login/form", 
			$this->_data,
			array(
				'header_template_path' => '/templates/login-header',
				'footer_template_path' => '/templates/login-footer',
			)
		);
	}
}




















