<?php

$DEFAULT_UNIQUE_ID = "00000";

require_once( "Base.class.php" );

Class Post extends Base {

	private $body;
	
	private $dateCreated;
	private $datePublished = '0000-00-00 00:00:00';
	
	private $status;
	
	function setBody( $body ) { $this -> body = $body; }
	
	function getBody() { return $this -> body; }
	
	function setDateCreated( $dateCreated ) { $this -> dateCreated = $dateCreated; }
	
	function getDateCreated() { return $this -> dateCreated; }
	
	function setDatePublished( $datePublished ) { $this -> datePublished = $datePublished; }
	
	function getDatePublished() { return $this -> datePublished; }
	
	function setStatus( $status ) { $this -> status = $status; }
	
	function getStatus() { return $this -> status; }
	
	function saveToDB( $returnType = 0 ) {
		
		GLOBAL $dbh;
		
		$query = '
INSERT INTO `postDetails` (
	  `uniqueID`
	, `body`
)
VALUES (
	  "' .  $this -> getUniqueID() . '"
	, "' .  $this -> getBody() . '"
)';
	
		switch( $returnType ) {
			
			case "0" : {
		
				$returnValue = false;
		
				try {
					
					$dbh -> beginTransaction();

						$dbh -> exec( $query );
				   
					$dbh -> commit();
						
					$returnValue = true;
				   
				} 
				catch( PDOException $e ) {
					
				   print "Error[ 101 ]: " . $e -> getMessage();			   
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

	function loadFromDB( $returnType = 0 ) {
		
		GLOBAL $dbh;
		
		$query = '
SELECT
	  `body`
	, `dateCreated`
	, `datePublished`
FROM
	`postDetails`
WHERE
	`uniqueID` = "' .  $this -> getUniqueID() . '"';
	
		switch( $returnType ) {
			
			case "0" : {
			
				$returnValue = false;
				
				try {

					$statement = $dbh -> prepare( $query );
					$statement -> execute();
					
					$row = $statement -> fetch();
					
					$this -> setBody( $row[ "body" ] );
					$this -> setDateCreated( $row[ "dateCreated" ] );
					$this -> setDatePublished( $row[ "datePublished" ] );
					
					$returnValue = true;
					
				} 
				catch( PDOException $e ) {
					
				   print "Error!: " . $e -> getMessage();			   
				   die();
				   
				}
				
			}
			break;
			
			case "1" : {
			
				$returnValue = $query;
				
			}
		
		}
				
		return $returnValue;
	
	}
		
	function updateDB( $returnType = 0 ) {
		
		GLOBAL $dbh;
		
		$query = '
UPDATE
	`postDetails`
SET
	  `body` = "' .  $this -> getBody() . '"
	, `dateCreated` = "' .  $this -> getDateCreated() . '"
	, `datePublished` = "' .  $this -> getDatePublished() . '"
WHERE
	`uniqueID` = "' . $this -> getUniqueID() . '"';
		
		if( $returnType == "bool" ) {
		
			$returnValue = false;
			
			try {

				$statement = $dbh -> prepare( $query );
				$statement -> execute();
				
				$returnValue = true;
				
			} 
			catch( PDOException $e ) {
				
			   print "Error!: " . $e -> getMessage();			   
			   die();
			   
			}		
			
		}
		else {
			
			$returnValue = $query;
		
		}		
		
		return $returnValue;
	
	}
	
	function __construct( $uniqueID = "00000",
	                      $body = "" ) {

		parent::__construct( $uniqueID );

		if( $uniqueID == "00000" ) {

			$this -> setBody( $body );

		}
		else {

			$this -> loadFromDB();

		}

	}

}

?>
