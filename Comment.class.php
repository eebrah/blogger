<?php

require_once( "Post.class.php" );

Class Comment extends Post {

	private $article;
	
	function setArticle( $article ) { $this -> article = $article; }
	
	function getArticle() { return $this -> article; }
	
	function saveToDB( $returnType = 0 ) {
		
		GLOBAL $dbh;
		
		$query = '
INSERT INTO `commentDetails` (
	  `uniqueID`
	, `article`
)
VALUES (
	  "' . mysql_escape_string( $this -> getUniqueID() ) . '"
	, "' . mysql_escape_string( $this -> getArticle() ) . '"
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
	`article`
FROM
	`commentDetails`
WHERE
	`uniqueID` = "' . mysql_escape_string( $this -> getUniqueID() ) . '"';
	
		switch( $returnType ) {
			
			case "0" : {
			
				$returnValue = false;
				
				try {

					$statement = $dbh -> prepare( $query );
					$statement -> execute();
					
					$row = $statement -> fetch();
					
					$this -> setArticle( $row[ "article" ] );
					
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
	`commentDetails`
SET
	`article` = "' . mysql_escape_string( $this -> getArticle() ) . '"
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
	                      $article = "" ) {

		parent::__construct( $uniqueID, $body );

		if( $uniqueID == "00000" ) {

			$this -> setArticle( $article );

		}
		else {

			$this -> loadFromDB();

		}

	}

}

function getComments( $returnType = 0, $filter = "all" ) {

	GLOBAL $dbh;

	$query = '
SELECT
	`uniqueID`
FROM
	`commentDetails`
WHERE';

	if( $filter == "all" ) {

		$query .= '
	1';

	}
	else {

		// more to come?

	}

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

function commentExists( $returnType = 0, $CommentID ) {

	GLOBAL $dbh;

	$query = '
SELECT
	`uniqueID`
FROM
	`commentDetails`
WHERE
	`uniqueID` = "' . $CommentID . '"';

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
