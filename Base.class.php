<?php

$DEFAULT_UNIQUE_ID = "00000";

require_once( "DBConfig.php" );

date_default_timezone_set( "Africa/Nairobi" );

try {

	$dbh = new PDO( 'mysql:host=' . $DBHost . ';dbname=' . $DBName, $DBUser, $DBPass, array( PDO::ATTR_PERSISTENT => true ) );
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

}
catch( PDOException $e ) {

	print "Error!: " . $e -> getMessage();

	die();

}

function genRandomString( $length = 5, $seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ) {

	$returnValue = '';

	for( $i = 0; $i < $length; $i++ ) {

		$returnValue .= $seed[ rand( 0, strlen( $seed ) - 1 ) ];

	}

	return $returnValue;

}

Class Base {

	private $uniqueID;

	function setUniqueID( $uniqueID ) {

		$this -> uniqueID = $uniqueID;

	}

	function getUniqueID() {

		return $this -> uniqueID;

	}

	function genUniqueID( $length = 5, $seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ) {

		$returnValue = '00000';

		$returnValue = genRandomString( $length, $seed );

		$this -> setUniqueID( $returnValue );

	}

	function __construct( $uniqueID = "00000", $length = 5 ) {

		if( $uniqueID == "00000" ) {

			$this -> genUniqueID( $length );

		}
		else {

			$this -> setUniqueID( $uniqueID );

		}

	}

}

?>
