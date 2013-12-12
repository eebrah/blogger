<?php

require_once( "Post.class.php" );

Class Comment extends Post {

	private $article;
	
	private $author;
	private $email;	
	
	function setArticle( $article ) { $this -> article = $article; }
	
	function getArticle() { return $this -> article; }
	
	function setAuthor( $author ) { $this -> author = $author; }
	
	function getAuthor() { return $this -> author; }
	
	function setEmail( $email ) { $this -> email = $email; }
	
	function getEmail() { return $this -> email; }
	
	function saveToDB( $returnType = 0 ) {
		
		GLOBAL $dbh;
		
		$query = '
INSERT INTO `commentDetails` (
	  `uniqueID`
	, `article`
	, `author`
	, `email`
)
VALUES (
	  "' . $this -> getUniqueID() . '"
	, "' . $this -> getArticle() . '"
	, "' . $this -> getAuthor() . '"
	, "' . $this -> getEmail() . '"
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
	, `author`
	, `email`
FROM
	`commentDetails`
WHERE
	`uniqueID` = "' . $this -> getUniqueID() . '"';
	
		switch( $returnType ) {
			
			case "0" : {
			
				$returnValue = false;
				
				try {

					$statement = $dbh -> prepare( $query );
					$statement -> execute();
					
					$row = $statement -> fetch();
					
					$this -> setArticle( $row[ "article" ] );
					$this -> setAuthor( $row[ "author" ] );
					$this -> setEmail( $row[ "email" ] );
					
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
		
		switch( $returnType ) {
			
			case "0" : {
		
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
			break;
			
			case "1" : {
				
				$returnValue = $query . parent::updateDB( 1 );
			
			}
			break;
			
		}
		
		return $returnValue;
	
	}
	
	function __construct( $uniqueID = "00000",
	                      $body = "",
	                      $article = "",
	                      $author = "anonymouse",
	                      $email = "user@mail.com" ) {

		parent::__construct( $uniqueID, $body );

		if( $uniqueID == "00000" ) {

			$this -> setArticle( $article );
			
			$this -> setAuthor( $author );
			
			$this -> setEmail( $email );

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
	  `commentDetails`.`uniqueID` 
	, `postDetails`.`dateCreated`
FROM 
	`commentDetails` 
	INNER JOIN 
		`postDetails` 
	ON  
		`commentDetails`.`uniqueID` = `postDetails`.`uniqueID`
WHERE';

	switch( $filter ) {
		
		case "approved" : {

	$query .= '
	`postDetails`.`status` = 1';

		}
		break;
		
		case "pending" : {

	$query .= '
	`postDetails`.`status` = 0';

		}
		break;
		
		case "rejected" : {

	$query .= '
	`postDetails`.`status` = 2';

		}
		break;
		
		case "all" :
		default : {

	$query .= '
	1';
		}
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
