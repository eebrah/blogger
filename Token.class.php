<?php

require_once( "Base.class.php" );

Class Token extends Base {

	private $account;
	private $type;
	private $status = 0;
	private $timeStamp;

	function setAccount( $accountID ) {

		$this -> account = $accountID;

	}

	function getAccount() {

		return $this -> account;

	}

	function setType( $type ) {

		$this -> type = $type;

	}

	function getType() {

		return $this -> type;

	}

	function setStatus( $status ) {

		$this -> status = $status;

	}

	function getStatus() {

		return $this -> status;

	}

	function setTimeStamp( $timestamp ) {

		$this -> timeStamp = $timestamp;

	}

	function getTimeStamp() {

		return $this -> timeStamp;

	}

	function saveToDB( $returnType = 0 ) {

		GLOBAL $dbh;

		$query = '
INSERT INTO `tokenDetails` (
	  `uniqueID`
	, `accountID`
	, `type`
)
VALUES (
	  "' . $this -> getUniqueID() . '"
	, "' . $this -> getAccount() . '"
	, "' . $this -> getType() . '"
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
	  `accountID`
	, `type`
	, `status`
	, `timeStamp`
FROM
	`tokenDetails`
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

					$this -> setAccount( $row[ "accountID" ] );
					$this -> setType( $row[ "type" ] );
					$this -> setStatus( $row[ "status" ] );
					$this -> setTimeStamp( $row[ "timeStamp" ] );

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
	`tokenDetails`
SET
	  `accountID` = "' .  $this -> getAccount() . '"
	, `type` = "' .  $this -> getType() . '"
	, `status` = "' .  $this -> getStatus() . '"
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
	                      $accountID = "",
	                      $type = 0 ) {

		parent::__construct( $uniqueID, 30 );

		if( $uniqueID == "00000" ) {

			if( $accountID != "00000" ) {

				$this -> setAccount( $accountID );

			}

			$this -> setType( $type );

		}
		else {

			$this -> loadFromDB();

		}

	}

}

function tokenExists( $returnType = 0, $tokenType = 0, $tokenID = "00000" ) {

	GLOBAL $dbh;

	$query = '
SELECT
	`uniqueID`
FROM
	`tokenDetails`
WHERE
	`uniqueID` = "' . $tokenID . '"
AND
	`type` = 0';

	switch( $returnType ) {

		case "0" :
		default : {

			$returnValue = true;

			try {

				$statement = $dbh -> prepare( $query );
				$statement -> execute();

				$results = $statement -> fetchAll();

				if( count( $results ) > 0 ) {

					$returnValue = true;

				}

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

		case "2" : {}
		break;

	}

	return $returnValue;

}

function getUserIDFromToken( $returnType = 0, $token ) {

	GLOBAL $dbh;

	$query = '
SELECT
	`accountID`
FROM
	`tokenDetails`
WHERE
	`uniqueID` = "' . $token . '"';

	switch( $returnType ) {

		case "0" :
		default : {

			$returnValue = true;

			try {

				$statement = $dbh -> prepare( $query );
				$statement -> execute();

				$result = $statement -> fetch();

				$returnValue = $result[ "accountID" ];

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
