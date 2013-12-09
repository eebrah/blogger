<?php

require_once("admin_creator.class.php");

$creator = new AdminCreator();


function email_is_valid($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function errors_found( $creator ) {
	$errors_found = false;
	
	if($_POST['cms-admin-user'] !== "") {
		$creator->remove_invalid_user_error();
	} else {
		$errors_found = true;
	}
	
	if($_POST['cms-admin-pass'] !== "") {
		$creator->remove_invalid_pass_error();
	} else {
		$errors_found = true;
	}
	
	if($_POST['cms-admin-conf-pass'] === $_POST['cms-admin-pass']) {
		$creator->remove_pass_mismatch_error();
	} else {
		$errors_found = true;
	}
	
	if($_POST['cms-admin-email'] !== "") {
		$creator->remove_empty_email_error();
	} else {
		$errors_found = true;
	}
	
	if(email_is_valid($_POST['cms-admin-email'])) {
		$creator->remove_invalid_email_error();
	} else {
		$errors_found = true;
	}
	
	return $errors_found;
}

if(isset($_POST['cms-admin-user'])) {
	
	if ( errors_found($creator) ) {
		$creator->repopulate_username($_POST['cms-admin-user']);
		$creator->repopulate_email($_POST['cms-admin-email']);
		$creator->render();
	} else {
		$username = $_POST['cms-admin-user'];
		$email = $_POST['cms-admin-email'];
		$pass = $_POST['cms-admin-pass'];
		$conf_pass = $_POST['cms-admin-conf-pass'];
		
		$creator->set_username($username);
		$creator->set_email($email);
		$creator->set_password($pass);
		$creator->run();
	}
	
	
} else {
	$creator->remove_all_errors();
	$creator->render();
}

?>
