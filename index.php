<?php

// This bit ensures that all output is GZip compressed, saving bandwidth

if( substr_count( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) ) {
	ob_start( "ob_gzhandler" );

}
else {
	ob_start();

}

require_once( "./User.class.php" );
require_once( "./Token.class.php" );
require_once( "./Article.class.php" );

require_once( "./markdown.php" );

session_start();

/** Install the app if necessary **/
if ( file_exists("install") ) {
	header("Location: install.php");
}

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

$pageBody = '';

}

$section = "articles";

if( isset( $_REQUEST[ "section" ] ) ) {
	
	$section = $_REQUEST[ "section" ];

}

switch( $section ) {
	
	case "home" : {}
	break;
	
	case "articles" : {
		
		$articles = getArticles();
		
		if( count( $articles ) > 0 ) {
			
			$count = 5;
			
			if( count( $articles ) <= 5 ) {
			
				$limit = count( $articles );
			
			}
			
			for( $i = 0; $i < $limit; $i++ ) {
		
				$article = new Article( $articles[ $i ] );
				
				if( articleExists( 0, $articles[ $i ] ) ) {
					
					$pageBody .= '
<div class="article">
	<h1>' . $article -> getTitle() . '</h1>
' . Markdown( $article -> getBody() ) . '</div>';
		
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
							<a href="?section=articles&amp;action=view&amp;target=">view</a>
						</li>
						<li>
							<a href="?section=articles&amp;action=edit&amp;target=">edit</a>
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
			
			case "view" : {
				
				if( isset( $_REQUEST[ "target" ] ) ) {
					
					$article = new Article( $_REQUEST[ "target" ] );
					
					$pageBody .= '
<div class="article">
	<h1>' . $article -> getTitle() . '</h1>
	' . Markdown( $article -> getBody ) . '
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
