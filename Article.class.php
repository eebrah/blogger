<?php

require_once( "Post.class.php" );

Class Article extends Post {

	private $title;
	
	function setTitle( $title ) { $this -> title = $title; }
	
	function getTitle() { return $this -> title; }
	
	function saveToDB( $returnType = 0 ) {
		
		GLOBAL $dbh;
		
		$query = '
INSERT INTO `articleDetails` (
	  `uniqueID`
	, `title`
)
VALUES (
	  "' . mysql_escape_string( $this -> getUniqueID() ) . '"
	, "' . mysql_escape_string( $this -> getTitle() ) . '"
)';
	
		switch( $returnType ) {
			
			case "0" : {
		
				$returnValue = false;
		
				try {
					
					$dbh -> beginTransaction();

						$dbh -> exec( parent::saveToDB( 1 ) );

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
		
		parent::loadFromDB();
		
		$query = '
SELECT
	`title`
FROM
	`articleDetails`
WHERE
	`uniqueID` = "' . mysql_escape_string( $this -> getUniqueID() ) . '"';
	
		switch( $returnType ) {
			
			case "0" : {
			
				$returnValue = false;
				
				try {

					$statement = $dbh -> prepare( $query );
					$statement -> execute();
					
					$row = $statement -> fetch();
					
					$this -> setTitle( $row[ "title" ] );
					
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
	`articleDetails`
SET
	`title` = "' . mysql_escape_string( $this -> getTitle() ) . '"
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
	                      $body = "",
	                      $title = "" ) {

		parent::__construct( $uniqueID, $body );

		if( $uniqueID == "00000" ) {

			$this -> setTitle( $title );

		}
		else {

			$this -> loadFromDB();

		}

	}

}

?>
