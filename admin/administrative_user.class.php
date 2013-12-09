<?php

require_once("../Account.class.php");

class AdministrativeUser extends Account {
	private $access_level = 0;
	private $email;
	
	public function getEmail() {
		return $this->email;
	}
	
	function saveToDB( $returnType = 0 ) {
		GLOBAL $dbh;
		
		$query = 'INSERT INTO `accountDetails` (
			`uniqueID`, `screenName`, `password`, `email`, `accessLevel`
			) VALUES ("'
				.$this -> getUniqueID() . '", "'
				.$this -> getScreenName() . '", "'
				.hash( "md5", $this -> getPassword() ) . '", "'
				.$this -> getEmail() . '", "'
				.$this->access_level .'")';

		switch( $returnType ) {
			case "0" :
			default : {		// return a boolean result
				
				$returnValue = false;

				try {
					$dbh -> beginTransaction();
						$dbh -> exec( $query );
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
			$this->email = $email;
			parent::__construct($uniqueID, $screenName, $password, $email);
	}
}
?>
