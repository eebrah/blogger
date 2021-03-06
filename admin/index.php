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
require_once( "../kses.php" );

$allowed = array('b' => array(),
                 'i' => array(),
                 'pre' => array('class' => 1),
                 'a' => array('href' => 1, 'title' => 1),
                 'p' => array('align' => 1),
                 'br' => array());

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
	
	$articles = getArticles( 0, "all" );

{
	$pageBody .= '
				<div class="sideColumn">
					<ul>
						<li>
							<a href="?section=articles">articles</a>
							<ul>
								<li>
									<a href="?section=articles&amp;action=list">all <sup>[ ' . count( getArticles( 0, "all" ) ) . ' ]</sup></a>
								</li>
								<li>
									<a href="?section=articles&amp;action=list&amp;filter=published">published <sup>[ ' . count( getArticles( 0, "published" ) ) . ' ]</sup></a>
								</li>
								<li>
									<a href="?section=articles&amp;action=list&amp;filter=pending">pending <sup>[ ' . count( getArticles( 0, "pending" ) ) . ' ]</sup></a>
								</li>
								<li>
									<a href="?section=articles&amp;action=list&amp;filter=withdrawn">withdrawn <sup>[ ' . count( getArticles( 0, "withdrawn" ) ) . ' ]</sup></a>
								</li>
								<li>
									<a href="?section=articles&amp;action=add">new</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="?section=comments">comments</a>
							<ul>
								<li>
									<a href="?section=comments&amp;action=list">all<sup> [ ' . count( getComments( 0, "all" ) ) . ' ]</sup></a>
								</li>
								<li>
									<a href="?section=comments&amp;action=list&amp;filter=pending">pending<sup> [ ' . count( getComments( 0, "pending" ) ) . ' ]</sup></a>
								</li>
								<li>
									<a href="?section=comments&amp;action=list&amp;filter=approved">approved<sup> [ ' . count( getComments( 0, "approved" ) ) . ' ]</sup></a>
								</li>
								<li>
									<a href="?section=comments&amp;action=list&amp;filter=rejected">suspended<sup> [ ' . count( getComments( 0, "rejected" ) ) . ' ]</sup></a>
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

						$currentUser -> setScreenName( $_POST[ "screenName" ] );
						$currentUser -> setName( $_POST[ "name" ] );
						
						if( $currentUser -> updateDB() ) {
							
							$pageBody .= '
<div class="message">
	<p>your details have been updated</p>
</div>';
						
						}
						else {
							
							$pageBody .= '
<div class="message">
	<p>Could not update your details</p>
</div>';
						
						}

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
					
					$filter = "all";
					
					if( isset( $_REQUEST[ "filter" ] ) ) {
					
						$filter = $_REQUEST[ "filter" ];
					
					}
					
					$articles = getArticles( 0, $filter );
					
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
						<ul class="actions">
							<li>
								<a href="?section=articles&amp;action=view&amp;target=' . $articleID . '">view</a>
							</li>
							<li>
								<a href="?section=articles&amp;action=edit&amp;target=' . $articleID . '">edit</a>
							</li>
							<li>
								<a href="?section=articles&amp;action=toggle&amp;target=' . $articleID . '">toggle</a>
							</li>
						</ul>
					</td>
				</tr>';
					
							$count++;
				
						}
				
						$pageBody .= '
			</tbody>
		</table>
	</div>';

					}
					else {
						
						$pageBody .= '
	<div class="dialog">
		<p>no articles matching your criteria were found</p>
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
				<input type="hidden"
				       name="target"
					   value="' . $_REQUEST[ "target" ] . '" />
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
			' . kses( Markdown( $article -> getBody() ), $allowed ) . '
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
			
				case "toggle" : {
					
					if( isset( $_REQUEST[ "target" ] ) ) {
						
						$article = new article( $_REQUEST[ "target" ] );
						
						switch( $article -> getStatus() ) {
							
							case "0" : {
							
								$article -> setStatus( 1 );
								$article -> setDatePublished( date( "Y-m-d H:i:s" ) );
								
								$message = 'article approved';
								
							}
							break;
							
							case "1" : {
								
								$article -> setStatus( 2 );
								
								$message = 'article suspended';
								
							}
							break;
							
							case "2" : {
							
								$article -> setStatus( 1 );
								
								$message = 'article approved';
								
							}
							break;
							
							default : {
								
								$article -> setStatus( 0 );
							
							}
							break;
						
						}
						
						if( $article -> updateDB() ) {

							$pageBody .= '
<div>
	<p>' . $message . '!</p>
</div>';
						
						}
						else {
							
							$pageBody .= '
<div>
	<p>could not toggle article :(</p>
</div>';
						
						}

					}
					else {
						
						$pageBody .= '
	<div class="dialog">
		<p>you have to specify an article whose status to modify</p>
	</div>';
							
					}
				
				}
				break;
			
			}
		
		} 
		break;
		
		case "comments" : {
			
			$pageBody .= '<h1>comments</h1>';
			
			$action = "list";
			
			if( isset( $_REQUEST[ "action" ] ) ) {
				
				$action = $_REQUEST[ "action" ];
			
			}
			
			switch( $action ) {
				
				case "list" : {
					
					$filter = "all";
					
					if( isset( $_REQUEST[ "filter" ] ) ) {
						
						$filter = $_REQUEST[ "filter" ];
						
					}
					
					$comments = getComments( 0, $filter );
					
					if( count( $comments ) > 0 ) {
					
						$pageBody .= '
	<div>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>datetime</th>
					<th>article title</th>
					<th>comment</th>
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
					<td>' . kses( substr( $comment -> getBody(), 0, 30 ), $allowed ) . ' ...</td>
					<td>
						<ul class="actions">
							<li>
								<a href="?section=comments&amp;action=view&amp;target=' . $commentID . '">view</a>
							</li>
							<li>
								<a href="?section=comments&amp;action=toggle&amp;target=' . $commentID . '">toggle</a>
							</li>
						</ul>
					</td>
				</tr>';
				
								$count++;
				
							}
				
							$pageBody .= '
			</tbody>
		</table>
	</div>';

					}
					else {
					
						$pageBody .= '
	<div class="dialog">
		<p>no commentsmatching your criteria were found</p>
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
		' . kses( Markdown( $comment -> getBody() ), $allowed ) . '
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
			
				case "toggle" : {
					
					if( isset( $_REQUEST[ "target" ] ) ) {
						
						$comment = new Comment( $_REQUEST[ "target" ] );
						
						$message = 'wut?' . $comment -> getStatus();
						
						switch( $comment -> getStatus() ) {
							
							case "0" : {
							
								$comment -> setStatus( 1 );
								$comment -> setDatePublished( date( "Y-m-d H:i:s" ) );
								
								$message = 'comment approved';
								
							}
							break;
							
							case "1" : {
								
								$comment -> setStatus( 2 );
								
								$message = 'comment suspended';
								
							}
							break;
							
							case "2" : {
							
								$comment -> setStatus( 1 );
								
								$message = 'comment approved';
								
							}
							break;
							
							default : {
								
								$comment -> setStatus( 0 );
							
							}
							break;
						
						}
						
						if( $comment -> updateDB() ) {

							$pageBody .= '
<div>
	<p>' . $message . '!</p>
</div>';
						
						}
						else {
							
							$pageBody .= '
<div>
	<p>could not toggle comment :(</p>
</div>';
						
						}

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
