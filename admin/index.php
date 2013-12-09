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
require_once( "../Comment.class.php" );

require_once( "../markdown.php" );

session_start();

{ // page building variables

$url = 'ibrahimngeno.me.ke';					// set to your sites URL
	
$adminEmail = "eebrah@gmail.com";				// all "contact site admin" links will point to this

$HTMLEmailheaders = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: ' . $adminEmail . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

$output = '';

$pageTitle = 'netivity CMS : control panel';

$pageHeader = '<!DOCTYPE html>
<html>
	<head>
		<title>' . $pageTitle . '</title>
		<link type="text/css"
		      rel="stylesheet"
		      href="../styles/main.css.php">
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

$pageBody = '';

}




$section = "home";

if( isset( $_REQUEST[ "section" ] ) ) {
	
	$section = $_REQUEST[ "section" ];

}


if( isset( $_SESSION[ "blog" ][ "admin" ][ "loggedIn" ] ) ) {

	$currentUser = new User( $_SESSION[ "blog" ][ "admin" ][ "loggedIn" ] );

{
	$pageBody .= '
				<div class="sideColumn">
					<ul>
						<li>
							<a href="?section=articles">articles</a>
							<ul>
								<li>
									<a href="?section=articles&amp;action=list">list articles</a>
								</li>
								<li>
									<a href="?section=articles&amp;action=add">add new article</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="?section=comments">comments</a>
							<ul>
								<li>
									<a href="?section=comments&amp;action=list">list comments</a>
								</li>
							</ul>
						</li> <!--
						<li>
							<a href="?section=users">users</a>
							<ul>
								<li>
									<a href="?section=users&amp;action=list">list users</a>
								</li>
								<li>
									<a href="?section=users&amp;action=add">add new user</a>
								</li>
							</ul>
						</li> -->
						<li>
							<a href="?section=access">profile</a>
							<ul>
								<li>
									<a href="?section=profile&amp;action=view">view</a>
								</li>
								<li>
									<a href="?section=profile&amp;action=edit">edit</a>
								</li>
								<li>
									<a href="?section=access&amp;action=logOut">log out</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="mainColumn">';
}

	$section = "profile";

	if( isset( $_REQUEST[ "section" ] ) ) {

		$section = $_REQUEST[ "section" ];

	}

	switch( $section ) {

		case "access" : {

			$action = "logOut";

			if( isset( $_REQUEST[ "action" ] ) ) {

				$action = $_REQUEST[ "action" ];

			}

			switch( $action ) {

				case "logOut" :
				default : {

					unset( $_SESSION[ "blog" ][ "admin" ][ "loggedIn" ] );

					// Redirect
					$host = $_SERVER[ 'HTTP_HOST' ];
					$uri = rtrim( dirname( $_SERVER[ 'PHP_SELF' ] ), '/\\' );

					// If no headers are sent, send one
					if( !headers_sent() ) {

						header( "Location: http://" . $host . $uri . "/" );
						exit;

					}

				}
				break;

				case "unRegister" : {

					// Ask user to confirm

				}
				break;

			}

		}
		break;

		case "profile" :
		default : {

			$pageBody .= '<h2>profile</h2>';

			$action = "view";

			if( isset( $_REQUEST[ "action" ] ) ) {

				$action = $_REQUEST[ "action" ];

			}

			switch( $action ) {

				case "view" :
				default : {

					$pageBody .= '
<table>
	<tbody>
		<tr>
			<th>unique ID</th>
			<td>' . $_SESSION[ "blog" ][ "admin" ][ "loggedIn" ] . '</td>
		</tr>
		<tr>
			<th>name</th>
			<td>' . $currentUser -> getName() . '</td>
		</tr>
		<tr>
			<th>user name</th>
			<td>' . $currentUser -> getScreenName() . '</td>
		</tr>
	</tbody>
</table>';

				}
				break;

				case "edit" : {

					if( isset( $_POST[ "name" ] ) && isset( $_POST[ "screenName" ] ) ) {

						//Process the data

					}
					else {

						$pageBody .= '
<div class="dialog">
	<form action="?section=profile&amp;action=edit"
	      method="post">
		<fieldset class="info">
			<legend>personal details</legend>
			<div class="row">
				<label for="screenName">username</label>
				<input type="text"
				       name="screenName"
				       value="' . $currentUser -> getScreenName() . '"
				       pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{3,20}$"
				       title="must be between 3 and 20 characters long, acn only contain letters, numbers, - and _ and ." />
			</div>
			<div class="row">
				<label for="name">name</label>
				<input type="text"
				       name="name"
				       value="' . $currentUser -> getName() . '"
				       pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{3,80}$"
				       title="must be between 3 and 20 characters long, acn only contain letters" />
			</div><!--
			<div class="row">
				<label for="password0">password</label>
				<input type="password"
				       name="password0"
				       placeholder=""
				       pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{3,80}$"
				       title="must be between 3 and 80 characters long" />
			</div>
			<div class="row">
				<label for="password1">confirm password</label>
				<input type="password"
				       name="password1"
				       placeholder=""
				       pattern="" />
			</div> -->
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
					
						$pageBody .= '
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
					
								$pageBody .= '
				<tr>
					<td>' . $count . '</td>
					<td>' . $article -> getDateCreated() . '</td>
					<td>' . substr( $article -> getTitle(), 0, 30 ) . ' ...</td>
					<td>
						<ul>
							<li>
								<a href="?section=articles&amp;action=view&amp;target=' . $articleID . '">view</a>
							</li>
							<li>
								<a href="?section=articles&amp;action=edit&amp;target=' . $articleID . '">edit</a>
							</li>
						</ul>
					</td>
				</tr>';
				
						}
				
						$pageBody .= '
			</tbody>
		</table>
	</div>';

					}
					else {
						
						$pageBody .= '
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
							
							$pageBody .= '
	<div class="dialog">
		<p>Success!</p>
	</div>';
							
						}
						else {
							
							$pageBody .= '
	<div class="dialog">
		<p>Could not save to DB</p>
	</div>';
						
						}
					
					}
					else {
						
						$pageBody .= '
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
							
							$pageBody .= '
	<div class="dialog">
		<p>Success!</p>
	</div>';
								
							}
							else {
							
							$pageBody .= '
	<div class="dialog">
		<p>Could not update DB</p>
	</div>';
							
							}
						
						}
						else {
							
							$pageBody .= '
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
						
						$pageBody .= '
	<div class="dialog">
		<p>you have to specify an article to edit</p>
	</div>';
							
					}
				
				}
				break;
				
				case "view" : {
					
					if( isset( $_REQUEST[ "target" ] ) ) {
						
						if( articleExists( 0, $_REQUEST[ "target" ] ) ) {
						
							$article = new Article( $_REQUEST[ "target" ] );

							$pageBody .= '
		<div class="article">		
			<h1>' . $article -> getTitle() . '</h1>			
			' . Markdown( $article -> getBody() ) . '
			<p>' . $article -> getDateCreated() . '</p>
		</div>';
		
						}
						else {
							
							$pageBody .= '
<div class="dialog">
	<p>No such article!</p>
</div>';
						
						}
					
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
			
			$action = "list";
			
			if( isset( $_REQUEST[ "action" ] ) ) {
				
				$action = $_REQUEST[ "action" ];
			
			}
			
			switch( $action ) {
				
				case "list" : {
					
					$comments = getComments();
					
					if( count( $comments ) > 0 ) {
					
						$pageBody .= '
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
					
								$pageBody .= '
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
				
						$pageBody .= '
			</tbody>
		</table>
	</div>';

					}
					else {
						
						$pageBody .= '
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
							
							$pageBody .= '
	<div class="dialog">
		<p>Success!</p>
	</div>';
							
						}
						else {
							
							$pageBody .= '
	<div class="dialog">
		<p>Could not save to DB</p>
	</div>';
						
						}
					
					}
					else {
						
						$pageBody .= '
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
						
						$pageBody .= '
	<div class="comment">
		' . Markdown( $comment -> getBody ) . '
	</div>';
					
					}
					else {
						
						$pageBody .= '
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

	$pageBody .= '
				</div>';

}
else {

	$section = "access";

	if( isset( $_REQUEST[ "section" ] ) ) {

		$section = $_REQUEST[ "section" ];

	}

	switch( $section ) {

		case "access" : {

			$action = "logIn";

			if( isset( $_REQUEST[ "action" ] ) ) {

				$action = $_REQUEST[ "action" ];

			}

			switch( $action ) {

				case "logIn" : {

					if( isset( $_REQUEST[ "screenName" ] ) && isset( $_REQUEST[ "password" ] ) ) {

						$query = '
SELECT
	`uniqueID`
FROM
	`accountDetails`
WHERE
	`status` = "1"
AND
	`accessLevel` = "0"
AND
	`screenName` = "' . $_REQUEST[ "screenName" ] . '"
AND
	`password` = MD5( "' . $_REQUEST[ "password" ] . '" )
';

						try {

							if( $result = $dbh -> query( $query ) ) {

								$results = $result -> fetchAll();

								if( count( $results ) == 1 ) {

									$_SESSION[ "blog" ][ "admin" ][ "loggedIn" ] = $results[ 0 ] [ "uniqueID" ];

								}
								else {

									// More than one matching entry, something is very wrong

									$pageBody .= '<p>There were multiple entries</p>';

								}

							}
							else {

								$pageBody .= '
<div class="message">
<h4>Log In Error : 001</h4>
<p>There was an Error trying to log you in :(</p>
<p>Please contact the administrator if this persists</p>
</div>';

							}

						}
						catch( PDOException $e ) {

							print "Error!: " . $e -> getMessage() . "<br/>";

							die();

						}

						// Redirect
						$host = $_SERVER[ 'HTTP_HOST' ];
						$uri = rtrim( dirname( $_SERVER[ 'PHP_SELF' ] ), '/\\' );

						// If no headers are sent, send one
						if( !headers_sent() ) {

							header( "Location: http://" . $host . $uri . "/" );
							exit;

						}

					}
					else {
						$query = '
SELECT
	`uniqueID`
FROM
	`accountDetails`
WHERE
	`status` = "1"
AND
	`accessLevel` = "0"
';
						try {
							if( $result = $dbh -> query( $query ) ) {
								$results = $result -> fetchAll();
								if( count( $results ) == 0 ) {
									/** No Administrator - Create one **/
									header("Location: create_admin.php");
								}

							}
						}
						catch( PDOException $e ) {
							print "Error!: " . $e -> getMessage() . "<br/>";
							die();
						}

						$pageBody .= '
<div class="dialog" style="width: 30em; margin: 5em auto;">
	<form action="?section=access&amp;action=logIn"
	      method="post">
		<fieldset class="info">
			<legend>log in</legend>
			<div class="row">
				<label for="screenname">username</label>
				<input type="text"
				       name="screenName"
				       placeholder="your username"
					   required="required" />
			</div>
			<div class="row">
				<label for="password">password</label>
				<input type="password"
				       name="password"
				       placeholder="your password"
					   required="required" />
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

			}

		}
		break;

	}

}

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
