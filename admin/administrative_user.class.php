<?php

require_once("../User.class.php");

class AdministrativeUser extends User {
	private $access_level = 0;
	
	function saveToDB( $returnType = 0 ) {
		GLOBAL $dbh;
		
		$query_account = 'INSERT INTO `accountDetails` (
			`uniqueID`, `screenName`, `password`, `email`, `accessLevel`
			) VALUES ("'
				.$this -> getUniqueID() . '", "'
				.$this -> getScreenName() . '", "'
				.hash( "md5", $this -> getPassword() ) . '", "'
				.$this -> getEmail() . '", "'
				.$this->access_level .'")';
		
		$query_user = 'INSERT INTO `userDetails` (
			`uniqueID` , `name`
			) VALUES ("'
				.$this -> getUniqueID() . '", "'
				.$this -> getName() . '")';

		switch( $returnType ) {
			case "0" :
			default : {		// return a boolean result
				
				$returnValue = false;

				try {
					$dbh -> beginTransaction();
						$dbh -> exec( $query_account );
						$dbh -> exec( $query_user );
					$dbh -> commit();

					$returnValue = true;
				}
				catch( PDOException $e ) {
				   print "Error[ 101 ]: " . $e -> getMessage() . "<br/>";
				   die();
				}
			}
			break;

			case "1" : {	// return the query
				$returnValue = $query;
			}
			break;
		}

		return $returnValue;
	}
	
	function __construct( $uniqueID = "00000", $screenName = "",
		$password = "", $email = "" ) {
			parent::__construct($uniqueID, $screenName, $password, $email);
	}
}
?>
