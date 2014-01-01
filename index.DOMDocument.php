<?php

if( substr_count( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) ) { ob_start( "ob_gzhandler" ); } else {	ob_start(); }

session_start();

require_once( "./User.class.php" );
require_once( "./Token.class.php" );
require_once( "./Article.class.php" );
require_once( "./Comment.class.php" );

require_once( "./markdown.php" );


{ // page building variables

$url = '';						// set to your sites URL, may or may not be usefull
$name = 'Ibrahim';
$proffesion = 'web developer';
	
$adminEmail = "";				// all "contact site admin" links will point to this

$HTMLEmailheaders = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: ' . $adminEmail . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

$output = '';

$pageTitle = '';				// set the pages title here

}

$doc = new DOMDocument();

$doc -> validateOnParse = true;
$doc -> loadHTML( file_get_contents( 'template.xhtml' ) );

$mainColumn = $doc -> getElementById( "mainColumn" );

$commentsElement = $doc -> getElementById( "commentsDiv" );
$commentElementTemplate = $doc -> getElementById( "commentDiv" );
$commentFormSample = $doc -> getElementById( 'commentFormSample' );


{ // page building functions
	
function generateDialog( $message = "hello, world!" ) {
		
	GLOBAL $doc;	
					
	$dialog = $doc -> createElement( 'div' );
	
	$p = $doc -> createElement( 'p' );
	
	$text = $doc -> createTextNode( $message );
	$text = $p -> appendChild( $text );
			
	$p = $dialog -> appendChild( $p );

//	$dialog = $mainColumn -> appendChild( $dialog );
	$dialog -> setAttribute( "class", "dialog" );	
	
	return $dialog;
										
}

function generateComment( $commentID ) {
	
	GLOBAL $commentElementTemplate, $doc;
						
	$comment = new Comment( $commentID );
							
	$commentElement = $commentElementTemplate -> cloneNode( true );
	$commentElement -> removeAttribute( "id" );

	$ps = $commentElement -> getElementsByTagName( 'p' );
							
	$p = $ps -> item( 0 );
							
	$text = $doc -> createTextNode( 'on ' . substr( $comment -> getDateCreated(), 0, 10 ) . ' at ' . substr( $comment -> getDateCreated(), 11, 5 ) . ', ' . $comment -> getAuthor() . ' said :' );
	$text = $p -> appendChild( $text );
				
	$fragment = $doc -> createDocumentFragment();
	$fragment -> appendXML( Markdown( $comment -> getBody() ) );

	$commentElement -> appendChild( $fragment );
							
	return $commentElement;

}

function generatePost( $articleID ) {
	
	GLOBAL $doc, $commentsElement, $commentElementTemplate;
					
	$article = new Article( $articleID );
	 
	$articleElement = $doc -> createElement( 'div' );
	
	$articleHeader = $doc -> createElement( 'h1' );
	
	$text = $doc -> createTextNode( $article -> getTitle() );
	$text = $articleHeader -> appendChild( $text );
	
	$articleHeader = $articleElement -> appendChild( $articleHeader );

	$fragment = $doc -> createDocumentFragment();
	$fragment -> appendXML( Markdown( $article -> getBody() ) );

	$articleBody = $articleElement -> appendChild( $fragment );
	
	if( count( $article -> getComments() ) > 0 ) {
	
		foreach( $article -> getComments() as $commentID ) {			// Display each comment	
			
			$commentsElement -> appendChild( generateComment( $commentID ) );

		}

		$commentsElement -> removeChild( $commentElementTemplate );		// Remove the comment template

		$articleElement -> appendChild( $commentsElement );				// Attach the comments
		
	}					
	
	$articleElement -> setAttribute( "class", "article" );
	
	return $articleElement;

}

}


$section = "articles";

if( isset( $_REQUEST[ "section" ] ) ) {
	
	$section = $_REQUEST[ "section" ];

}

switch( $section ) {
	
	case "articles" : {
		
		$action = "list";
		
		if( isset( $_REQUEST[ "action" ] ) ) {
			
			$action = $_REQUEST[ "action" ];
		
		}
		
		switch( $action ) {
			
			case "list" : {
					
				$mainColumn -> removeChild( $commentsElement );	
				$mainColumn -> removeChild( $commentFormSample );	
					
				$articles = getArticles( 0, "published" );
				
				if( count( $articles ) > 0 ) {
					
					$limit = 5;
					
					if( count( $articles ) <= 5 ) {
					
						$limit = count( $articles );
					
					}
					
					for( $i = 0; $i < $limit; $i++ ) {
				
						$article = new Article( $articles[ $i ] );
						
						if( articleExists( 0, $articles[ $i ] ) ) {

							$articleElement = $doc -> createElement( 'div' );
							
							$href = $doc -> createElement( 'a' );
							$href = $articleElement -> appendChild( $href );
							$href -> setAttribute( 'href', '?section=articles&action=view&target=' . $articles[ $i ] );
							
							$articleHeader = $doc -> createElement( 'h1' );
							
							$text = $doc -> createTextNode( $article -> getTitle() );
							$text = $articleHeader -> appendChild( $text );
							
							$articleHeader = $href -> appendChild( $articleHeader );
	
							$articleElement = $mainColumn -> appendChild( $articleElement );
							$articleElement -> setAttribute( "class", "article" );
			
						}
						else {

							$mainColumn -> appendChild( generateDialog( 'Sorry, the article referenced no longer exists' ) );
	
						}
						
					}
					
				}
				else {
/*					
					$dialog = $doc -> createElement( 'div' );
					
					$p = $doc -> createElement( 'p' );
					
					$text = $doc -> createTextNode( 'no posts yet :(' );
					$text = $p -> appendChild( $text );
							
					$p = $dialog -> appendChild( $p );

					$dialog = $mainColumn -> appendChild( $dialog );
					$dialog -> setAttribute( "class", "dialog" );	
*/					
					$mainColumn -> appendChild( generateDialog( 'no posts yet :(' ) );
										
				}
							
			}
			break;
						
			case "view" : {
					
				$mainColumn -> removeChild( $commentsElement );	
				$mainColumn -> removeChild( $commentFormSample );	
				
				if( isset( $_REQUEST[ "target" ] ) ) { 
					
					$article = new Article( $_REQUEST[ "target" ] );
					
					$mainColumn -> appendChild( generatePost( $_REQUEST[ "target" ] ) );
					
					$commentForm = $commentFormSample -> cloneNode( true ); 	// get and attach the comment form
					$mainColumn -> appendChild( $commentForm );
					
				}
				else {
					
					$mainColumn -> appendChild( generateDialog( 'you have to specify an article to view' ) );
						
				}
			
			}
			break;
		
		}
	
	} 
	break;
	
	case "comments" : {
		
		$action = "add";
		
		if( isset( $_REQUEST[ "action" ] ) ) {
			
			$action = $_REQUEST[ "action" ];
		
		}
		
		switch( $action ) {
			
			case "add" :
			case "new" :
			default : {
				
				if( isset( $_POST[ "comment" ] ) ) {
					
					if( isset( $_POST[ "name" ] ) && isset( $_POST[ "email" ] ) && isset( $_POST[ "target" ] ) && ( filter_var( $_POST[ "email" ], FILTER_VALIDATE_EMAIL ) ) ) {
						
						$comment = new Comment( "00000", $_POST[ "comment" ], $_POST[ "target" ], $_POST[ "name" ], $_POST[ "email" ] );
						
						if( $comment -> saveToDB() ) {
					
							$mainColumn -> appendChild( generateDialog( 'your comment has been saved and is awaiting moderation, thank you for your feedback' ) );
							
						}
						else {
							
							$mainColumn -> appendChild( generateDialog( 'There was a problem saving your comment, please click back and try again' ) );
							
						}
					
					}
					else {
						
						$mainColumn -> appendChild( generateDialog( 'you must provide a valid email address and a name' ) );
											
					}
				
				}
				else {
					
					$mainColumn -> appendChild( generateDialog( 'you comment cannot be empty' ) );
				
				}
			
			}
			break;
	
		}
	
	}
	break;
	
}

header( 'Content-type: text/html' );

echo $doc -> saveHTML();	

?>
