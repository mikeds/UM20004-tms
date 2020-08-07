<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Send_mail extends Global_Controller {
	public function after_init() {
		$this->_today = date("Y-m-d H:i:s");
	}

	public function index(){
        $this->load->library('email');

        $config = Array(
            'protocol' => 'ssmtp',
            'smtp_host' => 'ssl://ssmtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_crypto' => 'security',
            'smtp_user' => getenv("SMTPUSERTEST"), // change it to yours
            'smtp_pass' => getenv("SMTPPASSTEST"), // change it to yours
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1',
            'wordwrap' => TRUE
        );
    
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        
        //Email content
        $htmlContent = '<h1>Sending email via Gmail SMTP server</h1>';
        $htmlContent .= '<p>This email has sent via Gmail SMTP server from CodeIgniter application.</p>';
        
        $this->email->to('marknel.pineda23@gmail.com');
        $this->email->from(getenv("SMTPUSERTEST"), 'TEST MAIL');
        $this->email->subject('How to send email via Gmail SMTP server in CodeIgniter');
        $this->email->message($htmlContent);

        if($this->email->send()) {
			echo "Email sent!";
		} else {
            echo $this->email->print_debugger();
        }
	}
}


