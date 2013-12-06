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
		
		$article = new Article( "DTMIU" );
		
//		if( $article -> saveToDB() ) {
			
			$pageContent .= '
<p>' . $article -> getUniqueID() . '</p>
<h1>' . $article -> getTitle() . '</h1>
<p>' . $article -> getBody() . '</p>';
/*		
		}
		else {
			
			$pageContent .= '
<p>Sorry, no save :(</p>';
		
		}
*/	
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
