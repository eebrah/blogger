<?php
require_once("../Constants.php");
require_once("../DBConfig.php");
require_once("installer.class.php");


$installer = new Installer();

function remove_install_tree($dir) {
	$files = array_diff(scandir($dir), array(".", ".."));
	foreach($files as $file) {
		if(is_dir("{$dir}/{$file}")) {
			remove_install_tree("{$dir}/{$file}");
		} else {
			unlink("{$dir}/{$file}");
		}
	}
	rmdir($dir);
}

if ( isset($_POST['mysql-root-user']) && ($_POST['mysql-root-user'] !== "") ) {
	if ( isset($_POST['mysql-root-pass']) && ($_POST['mysql-root-pass'] !== "") ) {

		$root_user = $_POST['mysql-root-user'];
		$root_pass = $_POST['mysql-root-pass'];		
		
		try {

			$conn = new PDO('mysql:host='.$DBHost.';', $root_user, $root_pass, array(PDO::ATTR_PERSISTENT=>true));
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			
			$installer->setRootUser($root_user);
			$installer->setRootPass($root_pass);
			$installer->setDBConn($conn);
			$installer->run();
			
			remove_install_tree("install");
			header("Location: ../index.php");
			
		} 
		catch ( \PDOException $pdoe ) {
			
			$exception_msg = substr($pdoe->getMessage(), 23, 13);
			if($exception_msg==="Access denied") {
				$installer->remove_invalid_data_errors();
				$installer->render();
			} else {
				print "Error[ 101 ]: " . $pdoe -> getMessage();
				die();
			}
		}
	} else {
		$installer->render();
	}
} else {
	$installer->remove_all_errors();
	$installer->render();
}
?>
