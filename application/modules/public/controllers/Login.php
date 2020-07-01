<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Public_Controller {

	public function after_init(){
		$this->set_scripts_and_styles();
		$this->load->model('admin/merchants_model', 'merchants');
	}

	public function index() {
		$this->_data['form_url']		= base_url() . "login";
		$this->_data['notification'] 	= $this->session->flashdata('notification');

		if ($_POST) {
			if ($this->form_validation->run('login')) {
				$username = $this->input->post("username");
				$password = $this->input->post("password");
				$password = hash("sha256", $password);

				$row_mobile = $this->merchants->get_datum(
					'',
					array(
						'CONCAT(merchant_mobile_country_code, merchant_mobile_no) ='	=> $username,
						'merchant_password'											=> $password,
						'merchant_status'												=> 1
					)
				)->row();

				$row_email = $this->merchants->get_datum(
					'',
					array(
						'merchant_email_address'	=> $username,
						'merchant_password'		=> $password,
						'merchant_status'			=> 1
					)
				)->row();

				if ($row_mobile == "" && $row_email == "") {
					// Invalid username or password
					$this->session->set_flashdata('notification', $this->generate_notification('danger', 'The username or password is/are incorrect!'));
					redirect($this->_data['form_url']);
				}

				$row = $row_email != "" ? $row_email : $row_mobile;

				$session[$this->_base_session] = array(
					'merchant_id'					=> $row->merchant_id,
					'merchant_fname'				=> $row->merchant_fname,
					'merchant_mname'				=> $row->merchant_mname,
					'merchant_lname'				=> $row->merchant_lname,
					'merchant_ext_name'				=> $row->merchant_ext_name,
					'merchant_mobile_country_code'	=> $row->merchant_mobile_country_code,
					'merchant_mobile_no'			=> $row->merchant_mobile_no,
					'member_avatar'					=> $this->generate_avatar($row->merchant_avatar_base64)
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




















