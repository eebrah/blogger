<?php

require_once("../Constants.php");
require_once("../libs/DOMTemplate/domtemplate.php");
require_once("administrative_user.class.php");

class AdminCreator {
	private $html_file = "/create_admin.html";
	private $template;
	
	private $username;
	private $email;
	private $password;
	
	public function __construct() {
		$this->template = DOMTemplate::fromFile("../".Constants::HTML_TEMPLATES_DIR.$this->html_file);
	}
	
	public function set_username($user) {
		$this->username = $user;
	}
	
	public function set_password($pass) {
		$this->password = $pass;
	}
	
	public function set_email($email) {
		$this->email = $email;
	}
	
	public function run() {
		GLOBAL $dbh;
		
		$admin = new AdministrativeUser("00000", $this->username, $this->password, $this->email);
		if($admin->saveToDB()) {
			header("Location: ./index.php");
		} else {
			/** report error saving to DB **/
		}
	}
	
	public function render() {
		echo $this->template->html();
	}
	
	public function repopulate_username($username) {
		$this->template->setValue("#cms-admin-user@value", $username);
	}
	
	public function repopulate_email($email) {
		$this->template->setValue("#cms-admin-email@value", $email);
	}
	
	public function remove_invalid_user_error() {
		$this->template->remove("#admin-user-err");
	}
	
	public function remove_invalid_email_error() {
		$this->template->remove("#admin-invalid-email-err");
	}
	
	public function remove_empty_email_error() {
		$this->template->remove("#admin-email-err");
	}
	
	public function remove_invalid_pass_error() {
		$this->template->remove("#admin-pass-err");
	}
	public function remove_pass_mismatch_error() {
		$this->template->remove("#admin-conf-pass-err");
	}
	
	public function remove_all_errors() {
		$this->remove_invalid_user_error();
		$this->remove_invalid_email_error();
		$this->remove_empty_email_error();
		$this->remove_invalid_pass_error();
		$this->remove_pass_mismatch_error();
	}
}
?>
