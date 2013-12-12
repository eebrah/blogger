<?php

require_once( "Post.class.php" );

Class Article extends Post {

	private $title;
	
	private $comments = Array();
	
	function setTitle( $title ) { $this -> title = $title; }
	
	function getTitle() { return $this -> title; }
	
	function addComment( $commentID ) { 
		
		if( !in_array( $commentID, $this -> comments ) ) {
		
			array_push( $this -> comments, $commentID );
			
		}
	
	}
	
	function getComments() { return $this -> comments; }
	
	function saveToDB( $returnType = 0 ) {
		
		GLOBAL $dbh;
		
		$query = '
INSERT INTO `articleDetails` (
	  `uniqueID`
	, `title`
)
VALUES (
	  "' . $this -> getUniqueID() . '"
	, "' . $this -> getTitle() . '"
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
	`uniqueID` = "' .  $this -> getUniqueID() . '"';
	
		$queryComments = '
SELECT 
	`commentDetails`.`uniqueID` 
FROM 
	`commentDetails` 
	INNER JOIN 
		`postDetails` 
	ON  
		`commentDetails`.`uniqueID` = `postDetails`.`uniqueID`
WHERE
	`commentDetails`.`article` = "' . $this -> getUniqueID() . '"
AND
	`postDetails`.`status` = 1';
	
		switch( $returnType ) {
			
			case "0" : {
			
				$returnValue = false;
				
				try {

					$statement = $dbh -> prepare( $query );
					$statement -> execute();
					
					$row = $statement -> fetch();
					
					$this -> setTitle( $row[ "title" ] );
					
					$statement = $dbh -> prepare( $queryComments );
					$statement -> execute();

					$results = $statement -> fetchAll( PDO::FETCH_ASSOC );

					foreach( $results as $result ) {

						$this -> addComment( $result[ "uniqueID" ] );

					}					
					
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
	`title` = "' .  $this -> getTitle() . '"
WHERE
	`uniqueID` = "' . $this -> getUniqueID() . '"';
		
		if( $returnType == "bool" ) {
		
			$returnValue = false;
			
			parent::updateDB();
			
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

function getArticles( $returnType = 0, $filter = "all" ) {

	GLOBAL $dbh;

	$query = '
SELECT 
	  `articleDetails`.`uniqueID` 
	, `postDetails`.`dateCreated`
FROM 
	`articleDetails` 
	INNER JOIN 
		`postDetails` 
	ON  
		`articleDetails`.`uniqueID` = `postDetails`.`uniqueID`
WHERE';

	switch( $filter ) {
		
		case "all" :
		default : {

			$query .= '
	1';
	
		}
		break;
		
		case "published" : {}
		break;
		
		case "pending" : {}
		break;
		
		case "retracted" : {}
		break;

	}
	
	$query .= '
ORDER BY
	`dateCreated` DESC';

	switch( $returnType ) {

		case "0" : {

			$returnValue = Array();

			try {

				$statement = $dbh -> prepare( $query );
				$statement -> execute();

				$results = $statement -> fetchAll();

				foreach( $results as $result ) {

					array_push( $returnValue, $result[ "uniqueID" ] );

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

	}

	return $returnValue;

}

function articleExists( $returnType = 0, $articleID ) {

	GLOBAL $dbh;

	$query = '
SELECT
	`uniqueID`
FROM
	`articleDetails`
WHERE
	`uniqueID` = "' . $articleID . '"';

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

	}

	return $returnValue;

}


?>
