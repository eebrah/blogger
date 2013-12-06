<?php

// This bit ensures that all output is GZip compressed, saving bandwidth

if( substr_count( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) ) {
	ob_start( "ob_gzhandler" );

}
else {
	ob_start();

}

require_once( "../User.class.php" );
require_once( "../Token.class.php" );
require_once( "../Article.class.php" );

session_start();

{ // page building variables

$url = 'ibrahimngeno.me.ke';					// set to your sites URL
	
$adminEmail = "eebrah@gmail.com";				// all "contact site admin" links will point to this

$HTMLEmailheaders = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: ' . $adminEmail . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

$output = '';

$pageTitle = 'netivity CMS';

$pageHeader = '<!DOCTYPE html>
<html>
	<head>
		<title>' . $pageTitle . '</title>
	</head>
	<body>
		<div class="wrapper">
			<div class="wrapper">
				<div class="header"></div>
				<div class="body">';
				
$pageFooter = '</div>
				<div class="footer"></div>
			</div>
		</div>
	</body>
</html>';

$pageContent = '';

}

$section = "home";

if( isset( $_REQUEST[ "section" ] ) ) {
	
	$section = $_REQUEST[ "section" ];

}

switch( $section ) {
	
	case "home" : {	
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
				
					$pageContent .= '
<div>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>datetime</th>
				<th>title</th>
				<th>actions</th>
			</tr>
		</thead>
		<tbody>';
		
						$count = 1;
				
						foreach( $articles as $articleID ) {
							
							$article = new Article( $articleID );
				
							$pageContent .= '
			<tr>
				<td>' . $count . '</td>
				<td>' . $article -> getDateCreated() . '</td>
				<td>' . substr( $article -> getTitle(), 0, 30 ) . ' ...</td>
				<td>
					<ul>
						<li>
							<a href="?section=articles&amp;action=view&amp;target=">view</a>
						</li>
						<li>
							<a href="?section=articles&amp;action=edit&amp;target=">edit</a>
						</li>
					</ul>
				</td>
			</tr>';
			
					}
			
					$pageContent .= '
		</tbody>
	</table>
</div>';

				}
				else {
					
					$pageContent .= '
<div class="dialog">
	<p>You have no articles</p>
</div>';
				
				}
			
			}
			break;
			
			case "add" : {

				if( isset( $_POST[ "body" ] ) && isset( $_POST[ "title" ] ) ) {
					
					$article = new Article( "00000", $_POST[ "body" ], $_POST[ "title" ] );
					
					if( $article -> saveToDB() ) {
						
						$pageContent .= '
<div class="dialog">
	<p>Success!</p>
</div>';
						
					}
					else {
						
						$pageContent .= '
<div class="dialog">
	<p>Could not save to DB</p>
</div>';
					
					}
				
				}
				else {
					
					$pageContent .= '
<div>
	<form action="?section=articles&amp;action=add"
	      method="post">
		<fieldset class="info">
			<legend>article info</legend>
			<div class="row">
				<label>title</label>
				<input type="text"
				       name="title"
				       placeholder="article title"
				       required="required" />
			</div>
			<div class="row">
				<label>article</label>
				<textarea name="body"
				          placeholder="type the article here"
				          required="reuired"></textarea>
			</div>
		</fieldset>
		<fieldset class="buttons">
			<button type="reset">reset</button>
			<button type="submit">submit</button>
		</fieldset>
	</form>
</div>';
					
				}
				
			}
			break;
			
			case "edit" : {
				
				if( isset( $_REQUEST[ "target" ] ) ) {
					
					$article = new Article( $_REQUEST[ "target" ] );
					
					if( isset( $_POST[ "body" ] ) && isset( $_POST[ "title" ] ) ) {
					
						$article -> setTitle( $_POST[ "title" ] );
						$article -> setBody( $_POST[ "body" ] );
						
						if( $article -> updateDB() ) {
						
						$pageContent .= '
<div class="dialog">
	<p>Success!</p>
</div>';
							
						}
						else {
						
						$pageContent .= '
<div class="dialog">
	<p>Could not update DB</p>
</div>';
						
						}
					
					}
					else {
						
						$pageContent .= '
<div>
	<form action="?section=articles&amp;action=edit"
	      method="post">
		<fieldset class="info">
			<legend>article info</legend>
			<div class="row">
				<label>title</label>
				<input type="text"
				       name="title"
				       value="' . $article -> getTitle() . '"
				       required="required" />
			</div>
			<div class="row">
				<label>article</label>
				<textarea name="body"
				          required="reuired">' . $article -> getBody() . '</textarea>
			</div>
		</fieldset>
		<fieldset class="buttons">
			<button type="reset">reset</button>
			<button type="submit">submit</button>
		</fieldset>
	</form>
</div>';
					
					}
				
				}
				else {
					
					$pageContent .= '
<div class="dialog">
	<p>you have to specify an article to edit</p>
</div>';
						
				}
			
			}
			break;
			
			case "view" : {
				
				if( isset( $_REQUEST[ "target" ] ) ) {
					
					$article = new Article( $_REQUEST[ "target" ] );
					
					$pageContent .= '
<div class="article">
	<h1>' . $article -> getTitle() . '</h1>
	' . Markdown( $article -> getBody ) . '
</div>';
				
				}
				else {
					
					$pageContent .= '
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
		
		$action = "list";
		
		if( isset( $_REQUEST[ "action" ] ) ) {
			
			$action = $_REQUEST[ "action" ];
		
		}
		
		switch( $action ) {
			
			case "list" : {
				
				$comments = getComments();
				
				if( count( $comments ) > 0 ) {
				
					$pageContent .= '
<div>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>datetime</th>
				<th>article title</th>
				<th>actions</th>
			</tr>
		</thead>
		<tbody>';
		
						$count = 1;
				
						foreach( $comments as $commentID ) {
							
							$comment = new Comment( $commentID );
							
							$article = new Article( $comment -> getArticle() );
				
							$pageContent .= '
			<tr>
				<td>' . $count . '</td>
				<td>' . $comment -> getDateCreated() . '</td>
				<td>' . substr( $article -> getTitle(), 0, 30 ) . ' ...</td>
				<td>
					<ul>
						<li>
							<a href="?section=comments&amp;action=view&amp;target=">view</a>
						</li>
						<li>
							<a href="?section=comments&amp;action=edit&amp;target=">edit</a>
						</li>
					</ul>
				</td>
			</tr>';
			
					}
			
					$pageContent .= '
		</tbody>
	</table>
</div>';

				}
				else {
					
					$pageContent .= '
<div class="dialog">
	<p>You have no comments</p>
</div>';
				
				}
			
			}
			break;
/*			
			case "add" : {

				if( isset( $_POST[ "body" ] ) && isset( $_POST[ "title" ] ) ) {
					
					$comment = new Comment( "00000", $_POST[ "body" ], $_POST[ "title" ] );
					
					if( $comment -> saveToDB() ) {
						
						$pageContent .= '
<div class="dialog">
	<p>Success!</p>
</div>';
						
					}
					else {
						
						$pageContent .= '
<div class="dialog">
	<p>Could not save to DB</p>
</div>';
					
					}
				
				}
				else {
					
					$pageContent .= '
<div>
	<form action="?section=comments&amp;action=add"
	      method="post">
		<fieldset class="info">
			<legend>comment info</legend>
			<div class="row">
				<label>title</label>
				<input type="text"
				       name="title"
				       placeholder="comment title"
				       required="required" />
			</div>
			<div class="row">
				<label>comment</label>
				<textarea name="body"
				          placeholder="type the comment here"
				          required="reuired"></textarea>
			</div>
		</fieldset>
		<fieldset class="buttons">
			<button type="reset">reset</button>
			<button type="submit">submit</button>
		</fieldset>
	</form>
</div>';
					
				}
				
			}
			break;
*/			
			case "view" : {
				
				if( isset( $_REQUEST[ "target" ] ) ) {
					
					$comment = new Comment( $_REQUEST[ "target" ] );
					
					$pageContent .= '
<div class="comment">
	' . Markdown( $comment -> getBody ) . '
</div>';
				
				}
				else {
					
					$pageContent .= '
<div class="dialog">
	<p>you have to specify an comment to view</p>
</div>';
						
				}
			
			}
			break;
		
		}
	
	}
	break;

}

$format = 'html';

if( isset( $_REQUEST[ "format" ] ) ) {
	
	$format = $_REQUEST[ "format" ];

}

switch( $format ) {
	
	case "html" : {
		
		$output = $pageHeader . $pageContent . $pageFooter;
	
	}
	break;
	
	case "ajax" : {
		
		$output = $pageContent;
	
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
