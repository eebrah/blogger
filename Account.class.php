<?php

require_once( "Base.class.php" );

Class Account extends Base {

	private $screenName;
	private $password;

	private $email;

	private $status = false;

	function setScreenName( $screenName ) {

		$this -> screenName = $screenName;

	}

	function getScreenName() {

		return $this -> screenName;

	}

	function setEmail( $email ) {

		$this -> email = $email;

	}

	function getEmail() {

		return $this -> email;

	}

	function setPassword( $password ) {

		$this -> password = $password;

	}

	function getPassword() {

		return $this -> password;

	}

	function setStatus( $status ) {

		$this -> status = $status;

	}

	function getStatus() {

		return $this -> status;

	}

	function saveToDB( $returnType = 0 ) {

		GLOBAL $dbh;

		$query = '
INSERT INTO `accountDetails` (
	  `uniqueID`
	, `screenName`
	, `password`
	, `email`
)
VALUES (
	  "' . $this -> getUniqueID() . '"
	, "' . $this -> getScreenName() . '"
	, "' . hash( "md5", $this -> getPassword() ) . '"
	, "' . $this -> getEmail() . '"
)';

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

	function loadFromDB( $returnType = 0 ) {

		GLOBAL $dbh;

		$query = '
SELECT
	  `screenName`
	, `password`
	, `status`
	, `email`
FROM
	`accountDetails`
WHERE
	`uniqueID` = "' .  $this -> getUniqueID() . '"';

		switch( $returnType ) {

			case "0" :
			default : {		// return a boolean result

				$returnValue = false;

				try {

					$statement = $dbh -> prepare( $query );
					$statement -> execute();

					$row = $statement -> fetch();

					$this -> setScreenName( $row[ "screenName" ] );
					$this -> setPassword( $row[ "password" ] );
					$this -> setStatus( $row[ "status" ] );
					$this -> setEmail( $row[ "email" ] );

					$returnValue = true;

				}
				catch( PDOException $e ) {

				   print "Error[ 102 ]: " . $e -> getMessage() . "<br/>";
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

	function updateDB( $returnType = 0 ) {

		GLOBAL $dbh;

		$query = '
UPDATE
	`accountDetails`
SET
	  `screenName` = "' .  $this -> getScreenName() . '"
	, `password` = "' .  $this -> getPassword() . '"
	, `status` = "' .  $this -> getStatus() . '"
	, `email` = "' . $this -> getEmail() . '"
WHERE
	`uniqueID` = "' .  $this -> getUniqueID() . '"';

		switch( $returnType ) {

			case "0" :
			default : {		// return a boolean result

				$returnValue = false;

				try {

					$statement = $dbh -> prepare( $query );
					$statement -> execute();

					$returnValue = true;

				}
				catch( PDOException $e ) {

				   print "Error[ 103 ]: " . $e -> getMessage() . "<br/>";
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

	function __construct( $uniqueID = "00000",
	                      $screenName = "",
	                      $password = "",
	                      $email = "" ) {

		parent::__construct( $uniqueID );

		if( $uniqueID == "00000" ) {

			if( $screenName != "" ) {

				$this -> setScreenName( $screenName );

			}

			if( $password != "" ) {

				$this -> setPassword( $password );

			}

			if( $email != "" ) {

				$this -> setEmail( $email );

			}

		}
		else {

			$this -> loadFromDB();

		}

	}

}

function getUserIDFromEmail( $returnType = 0, $needle ) {

	GLOBAL $dbh;

	$query = '
SELECT
	`uniqueID`
FROM
	`accountDetails`
WHERE
	`email` = "' . $needle . '"';

	switch( $returnType ) {

		case "0" :
		default : {

			$returnValue = true;

			try {

				$statement = $dbh -> prepare( $query );
				$statement -> execute();

				$result = $statement -> fetch();

				$returnValue = $result[ "uniqueID" ];

			}
			catch( PDOException $e ) {

			   print "Error!: " . $e -> getMessage() . "<br/>";
			   die();

			}


		}
		break;

		case "1" : {

			$returnValue = $query;

		}
		break;

	}

	return $returnValue;

}

?>
