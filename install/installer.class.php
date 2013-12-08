<?php

require_once("Base.class.php");
require_once("Constants.php");
require_once("DBConfig.php");
require_once("libs/DOMTemplate/domtemplate.php");


class Installer {
	private $html_file = "/install_install.html"; 
	private $template;
	private $db_structure_sql;
	
	/** database related fields **/
	private $root_pass;
	private $root_user;
	private $db_conn;
	
	
	
	public function __construct() {
		@session_start();
		$this->template = DOMTemplate::fromFile(Constants::HTML_TEMPLATES_DIR.$this->html_file);
		$this->db_structure_sql = file_get_contents("install/blog.sql");
	}
	
	public function setRootPass($root_pass) {
		$this->root_pass = $root_pass;
	}
	
	public function setRootUser($root_user) {
		$this->root_user = $root_user;
	}
	
	public function setDBConn($conn) {
		$this->db_conn = $conn;
	}
	
	public function render() {
		if( isset($_SESSION['db_created']) && ($_SESSION['db_created']==="true") ) {
			$this->remove_form();
			unset($_SESSION['db_created']);
		} else {
			$this->remove_db_installed_msg();
			$this->setup_form();
		}
		echo $this->template->html();
	}
	
	
	public function run() {
		GLOBAL $dbh;
		GLOBAL $DBName;
		GLOBAL $DBUser;
		GLOBAL $DBHost;
		GLOBAL $DBPass;
		
		$drop_sql = "DROP DATABASE IF EXISTS {$DBName}";
		$create_sql = "CREATE DATABASE {$DBName}";
		$user_sql = "GRANT ALL ON {$DBName}.* TO `{$DBUser}`@`{$DBHost}` IDENTIFIED BY '{$DBPass}'";
		$this->db_conn->beginTransaction();
			$this->db_conn->exec($drop_sql);
			$this->db_conn->exec($create_sql);
			$this->db_conn->exec($user_sql);
		$this->db_conn->commit();
		
		$dbh->beginTransaction();
			$dbh->exec($this->db_structure_sql);
		$dbh->commit();
		$_SESSION['db_created']="true";
	}
	
	public function remove_all_errors() {
		$this->template->remove("#installer-form/fieldset/span.error");
	}
	
	public function remove_invalid_data_errors() {
		$this->template->remove("#installer-form/fieldset/#root-user-err");
		$this->template->remove("#installer-form/fieldset/#root-pass-err");
	}
	
	private function setup_form() {
		$this->template->setValue("#installer-form@action","install.php");
	}
	
	public function remove_form() {
		$this->template->remove("#installer-form");
	}
	
	private function remove_db_installed_msg() {
		$this->template->remove("#db-installed-msg");
	}
}

?>
