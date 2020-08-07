<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_mail extends Global_Controller {
	public function after_init() {
		$this->_today = date("Y-m-d H:i:s");
	}

	public function index(){
        $this->load->library('email');

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => getenv("SMTPUSERTEST"), // change it to yours
            'smtp_pass' => getenv("SMTPPASSTEST"), // change it to yours
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1'
        );
    
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        
        //Email content
        $htmlContent = '<h1>Sending email via Gmail SMTP server</h1>';
        $htmlContent .= '<p>This email has sent via Gmail SMTP server from CodeIgniter application.</p>';
        
        $this->email->to('marknel.pineda23@gmail.com');
        $this->email->from('ibms2k18@gmail.com', 'TEST MAIL');
        $this->email->subject('How to send email via Gmail SMTP server in CodeIgniter');
        $this->email->message($htmlContent);
	}
}


