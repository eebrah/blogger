<?php

// This bit ensures that all output is GZip compressed, saving bandwidth

if( substr_count( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) ) { ob_start( "ob_gzhandler" ); } else {	ob_start(); }

session_start();

/** Install the app if necessary, by checking if the install folder exists and if so installing  **/
if ( file_exists( "install" ) ) { header( "Location: install/install.php" ); }

require_once( "./User.class.php" );
require_once( "./Token.class.php" );
require_once( "./Article.class.php" );
require_once( "./Comment.class.php" );

require_once( "./markdown.php" );
require_once( "./kses.php" );

$allowed = array('b' => array(),
                 'i' => array(),
                 'a' => array('href' => 1, 'title' => 1),
                 'p' => array('align' => 1),
                 'br' => array());

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

$pageHeader = '<!DOCTYPE html>
<html>
	<head>
		<title>' . $pageTitle . '</title>
		<link type="text/css"
		      rel="stylesheet"
		      href="./styles/blog.css.php">
	</head>
	<body>
		<div class="wrapper">
			<div class="header">
			</div>
			<div class="body">
				<div class="sideColumn">
					<p>Hi there, my name&apos;s ' . $name . ' and I am a ' . $proffesion . ' from Nairobi, Kenya</p>';
				
$pageFooter = '
				</div>
			</div>
			<div class="footer"></div>
		</div>
	</body>
</html>';

$pageBody = '';

}

$section = "articles";

if( isset( $_REQUEST[ "section" ] ) ) {
	
	$section = $_REQUEST[ "section" ];

}

switch( $section ) {
	
	case "home" : {
		
		if( isset( $_REQUEST[ "year" ] ) ) {
			
			$year = $_REQUEST[ "year" ];
					
			if( isset( $_REQUEST[ "month" ] ) ) {
				
				$month = $_REQUEST[ "month" ];
			
			}
			
		}
	
	}
	break;
	
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
							
							$pageBody .= '
	<div class="article">
		<a href="?section=articles&amp;action=view&amp;target=' . $articles[ $i ] . '">
			<h1>' . $article -> getTitle() . '</h1>
		</a>
	</div>';
			
						}
						else {
							
							$pageBody .= '
	<p>Sorry, the article referenced no longer exists</p>';
			
						}
						
					}
					
				}
				else {
				
					$pageBody .= '
	<div class="dialog">
		<p> no posts yet :( </p>
	</div>';
			
				}
							
			}
			break;
						
			case "view" : {
				
				if( isset( $_REQUEST[ "target" ] ) ) {
					
					$article = new Article( $_REQUEST[ "target" ] );
					
					$pageBody .= '
<div class="article">
	<h1>' . $article -> getTitle() . '</h1>
	' . kses( Markdown( $article -> getBody() ), $allowed ) . '
</div>
<div class="comments">';

					if( count( $article -> getComments() ) > 0 ) {
	
						foreach( $article -> getComments() as $commentID ) {
						
							$comment = new Comment( $commentID );

							$pageBody .= '
	<div class="comment">
		<p class="meta">on ' . substr( $comment -> getDateCreated(), 0, 10 ) . ' at ' . substr( $comment -> getDateCreated(), 11, 8 ) . ', ' . $comment -> getAuthor() . ' said :</p>
		' . kses( Markdown( $comment -> getBody() ), $allowed ) . '
	</div>';
	
						}
						
					}

					$pageBody .= '
</div>
<div class="commentForm">
	<form action="?section=comments&amp;action=new"
	      method="post">
		<fieldset class="info">
			<legend>please leave a comment</legend>
			<input type="hidden"
			       name="target"
			       value="' . $_REQUEST[ "target" ] . '" />
			<div class="row">
				<textarea name="comment"
				          placeholder="your comment here"></textarea>
			</div>
		</fieldset>
		<fieldset class="info identity">
			<div class="row">
				<!-- <label for="name">name</label> -->
				<input type="text"
				       name="name"
				       placeholder="your name"
				       required="required" />
			</div>
			<div class="row">
				<!-- <label for="email">email</label> -->
				<input type="email"
				       name="email"
				       placeholder="your email address [ will not be shared with anyone ]"
				       required="required" />
			</div>
		</fieldset>
		<fieldset class="buttons">
			<button type="submit">comment</button>
		</fieldset>
	</form>
</div>';
				
				}
				else {
					
					$pageBody .= '
<div class="dialog">
	<p>you have to specify an article to view</p>
</div>';
						
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
							
							$pageBody .= '
<div class="dialog">
	<p>your comment has been saved and is awaiting moderation, thank you for your feedback</p>
</div>';
						
						}
						else {
							
							$pageBody .= '
<div class="dialog">
	<p>There was a problem saving yourcomment</p>
</div>';
						
						}
					
					}
					else {
						
						$pageBody .= '
<div class="dialog">
	<p>you must provide a valid email address and a name</p>
</div>';
					
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


					
$pageHeader .= '
					</div>
					<div class="mainColumn">';


$format = 'html';

if( isset( $_REQUEST[ "format" ] ) ) {
	
	$format = $_REQUEST[ "format" ];

}

switch( $format ) {
	
	case "html" : {
		
		$output = $pageHeader . $pageBody . $pageFooter;
	
	}
	break;
	
	case "ajax" : {
		
		$output = $pageBody;
	
	}
	break;
	
	case "json" : {
		
	
	}
	break;
	
	case "xml" : {}
	break;
	
	case "pdf" : {}
	break;
	 
}

echo $output;

?>
