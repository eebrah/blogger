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
					
				$articles = getArticles();
				
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
							
							$dialog = $doc -> createElement( 'div' );
							
							$p = $doc -> createElement( 'p' );
							
							$text = $doc -> createTextNode( 'Sorry, the article referenced no longer exists' );
							$text = $p -> appendChild( $text );
									
							$p = $dialog -> appendChild( $p );
	
							$dialog = $mainColumn -> appendChild( $dialog );
							$dialog -> setAttribute( "class", "dialog" );
	
						}
						
					}
					
				}
				else {
					
					$dialog = $doc -> createElement( 'div' );
					
					$p = $doc -> createElement( 'p' );
					
					$text = $doc -> createTextNode( 'no posts yet :(' );
					$text = $p -> appendChild( $text );
							
					$p = $dialog -> appendChild( $p );

					$dialog = $mainColumn -> appendChild( $dialog );
					$dialog -> setAttribute( "class", "dialog" );	
										
				}
							
			}
			break;
						
			case "view" : {
				
				if( isset( $_REQUEST[ "target" ] ) ) {
					
					$article = new Article( $_REQUEST[ "target" ] );
					 
					$articleElement = $doc -> createElement( 'div' );
					
					$articleHeader = $doc -> createElement( 'h1' );
					
					$text = $doc -> createTextNode( $article -> getTitle() );
					$text = $articleHeader -> appendChild( $text );
					
					$articleHeader = $articleElement -> appendChild( $articleHeader );

					$fragment = $doc -> createDocumentFragment();
					$fragment -> appendXML( Markdown( $article -> getBody() ) );

					$articleBody = $articleElement -> appendChild( $fragment );

					$articleElement = $mainColumn -> appendChild( $articleElement );
					$articleElement -> setAttribute( "class", "article" );	
					
					 
					$commentsElement = $doc -> createElement( 'div' );	

					$commentsElement = $mainColumn -> appendChild( $commentsElement );
					$commentsElement -> setAttribute( "class", "comments" );	 					

					if( count( $article -> getComments() ) > 0 ) {
	
						foreach( $article -> getComments() as $commentID ) {
						
							$comment = new Comment( $commentID );
							 
							$commentElement = $doc -> createElement( 'div' );
					
							$p = $doc -> createElement( 'p' );
							
							$text = $doc -> createTextNode( 'on ' . substr( $comment -> getDateCreated(), 0, 10 ) . ' at ' . substr( $comment -> getDateCreated(), 11, 8 ) . ', ' . $comment -> getAuthor() . ' said :' );
							$text = $p -> appendChild( $text );
									
							$p = $commentElement -> appendChild( $p );
							$p -> setAttribute( "class", "meta" );							

							$fragment = $doc -> createDocumentFragment();
							$fragment -> appendXML( Markdown( $comment -> getBody() ) );

							$commentElement -> appendChild( $fragment );							

							$commentElement = $commentsElement -> appendChild( $commentElement );
							$commentElement -> setAttribute( "class", "comment" );

							$commentForm = $doc -> getElementById( 'commentFormSample' );
							
							//$mainColumn -> removeChild( $commentFormSample ); 
							$mainColumn -> appendChild( $commentForm );
								
						}
						
					}
					
				}
				else {
			
					$dialog = $doc -> createElement( 'div' );
					
					$p = $doc -> createElement( 'p' );
					
					$text = $doc -> createTextNode( 'you have to specify an article to view' );
					$text = $p -> appendChild( $text );
							
					$p = $dialog -> appendChild( $p );

					$dialog = $mainColumn -> appendChild( $dialog );
					$dialog -> setAttribute( "class", "dialog" );			
						
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
					
							$dialog = $doc -> createElement( 'div' );
							
							$p = $doc -> createElement( 'p' );
							
							$text = $doc -> createTextNode( 'your comment has been saved and is awaiting moderation, thank you for your feedback' );
							$text = $p -> appendChild( $text );
									
							$p = $dialog -> appendChild( $p );

							$dialog = $mainColumn -> appendChild( $dialog );
							$dialog -> setAttribute( "class", "dialog" );							
						/*	
							$pageBody .= '
<div class="dialog">
	<p>your comment has been saved and is awaiting moderation, thank you for your feedback</p>
</div>';
						*/
						}
						else {
							/*
							$pageBody .= '
<div class="dialog">
	<p>There was a problem saving yourcomment</p>
</div>';
						*/
							$dialog = $doc -> createElement( 'div' );
							
							$p = $doc -> createElement( 'p' );
							
							$text = $doc -> createTextNode( 'There was a problem saving your comment, please click back and try again' );
							$text = $p -> appendChild( $text );
									
							$p = $dialog -> appendChild( $p );

							$dialog = $mainColumn -> appendChild( $dialog );
							$dialog -> setAttribute( "class", "dialog" );
						}
					
					}
					else {
						
						$dialog = $doc -> createElement( 'div' );
						
						$p = $doc -> createElement( 'p' );
						
						$text = $doc -> createTextNode( 'you must provide a valid email address and a name' );
						$text = $p -> appendChild( $text );
								
						$p = $dialog -> appendChild( $p );

						$dialog = $mainColumn -> appendChild( $dialog );
						$dialog -> setAttribute( "class", "dialog" );
/*						
						$pageBody .= '
<div class="dialog">
	<p>you must provide a valid email address and a name</p>
</div>';
*/					
					}
				
				}
				else {
						
					$pageBody .= '
<div class="dialog">
	<p>you comment cannot be empty</p>
</div>';
					
				}
			
			}
			break;
	
		}
	
	}
	break;
	
}

/*
$text = $doc -> createTextNode( 'Hello, World!' );
$text = $paragraph -> appendChild( $text );


$dialog = $doc -> createElement( 'div' );
$dialog = $wrapper -> appendChild( $dialog );
$dialog -> setAttribute( "class", "dialog" );

$form = $doc -> createElement( 'form' );
$form = $dialog -> appendChild( $form );
$form -> setAttribute( "method", "post" );

$fieldset_info = $doc -> createElement( 'fieldset' ); 
$fieldset_info = $dialog -> appendChild( $fieldset_info );
$fieldset_info -> setAttribute( "class", "info" );


$row = $doc -> createElement( 'div' );
$row = $fieldset_info -> appendChild( $row );
$row -> setAttribute( "class", "row" );

$label = $doc -> createElement( 'label' );
$label = $row -> appendChild( $label );
$text = $doc -> createTextNode( 'tracking number :' );
$text = $label -> appendChild( $text );

$input = $doc -> createElement( 'input' );
$input = $row -> appendChild( $input );
$input -> setAttribute( "type", "text" );
$input -> setAttribute( "placeholder", "the parcel's tracking number" );


$fieldset_buttons = $doc -> createElement( 'fieldset' ); 
$fieldset_buttons = $dialog -> appendChild( $fieldset_buttons );
$fieldset_buttons -> setAttribute( "class", "buttons" );


$btn_reset = $doc -> createElement( 'button' );
$btn_reset = $fieldset_buttons -> appendChild( $btn_reset );
$btn_reset -> setAttribute( 'type', 'reset' );
$text = $doc -> createTextNode( 'reset' );
$text = $btn_reset -> appendChild( $text );


$btn_submit = $doc -> createElement( 'button' );
$btn_submit = $fieldset_buttons -> appendChild( $btn_submit );
$btn_submit -> setAttribute( 'type', 'submit' );
$text = $doc -> createTextNode( 'submit' );
$text = $btn_submit -> appendChild( $text );

*/



header( 'Content-type: text/html' );

echo $doc -> saveHTML();	

?>
