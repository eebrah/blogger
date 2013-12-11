<?php header( 'Content-type: text/css' ); if( substr_count( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) ) {	ob_start( "ob_gzhandler" ); } else { ob_start(); } ?>

* {
	padding: 0;
	margin: 0;

}

body {
	background-color: #EEEEEE;

	font-family: sans-serif;

}

	ul {
		list-style: none;

	}

	div.wrapper {
		background-color: white;

		max-width: 1000px;
		
		margin: 2em auto;

	}

	div.header {
		min-height: 10em;
		background-color: red;
	
		border-radius: 0.2em;
		
		margin: 1em 0;
	
	}

	div.body {
		width: 100%;

	}

		div.sideColumn {
			width: 26%;
			min-height: 20em;

			background-color: #122348;
			color: white;

			float: right;
			
			margin-left: 2%;
			padding: 1em 3%;
			
			border-radius: 0.2em;
			
		}

		div.mainColumn {
			width: 60%;
			min-height: 20em;

			background-color: white;
			color: black;

			float: right;
			
			padding: 0 3%;
			
			border-radius: 0.2em;
			
		}
		
		h1, h2, h3, h4 {
			margin: 1em 0 0.5em;
			
			border-bottom: 1px dotted;
			
			color: #122348;
		
		}
		
		.mainColumn blockquote {
			margin: 1em 0 1em 2em;
			padding: 0em 1em;
			
			border: 1px solid #666666;
			border-radius: 0.2em;
			
			background-color: #DDDDDD;
		
		}
		
		.mainColumn p {
			margin: 1em 0;
			line-height: 1.5em;
		
		}
		
		.mainColumn a {
			text-decoration: none;
		
		}
		
		.mainColumn li {
			margin-left: 2em;
			
			line-height: 1.5em;
			
			list-style: disc;
		
		}



fieldset.info {

	border: none;

}

fieldset.info legend {
	margin-bottom: 2em;

}

fieldset.info  label {
	font-size: 1em;
	font-weight: bold;
/*
	text-transform: uppercase; */

	margin: 0.5em 2%;

	float: left;
	text-align: right;
	width: 30%;

}

fieldset.info select {
	width: 60%;
	width: calc( 100% - ( 2% * 2 ) );

}

fieldset.info input, fieldset.info textarea {
	padding: 0.4em 2%;

	border: 1px solid #999999;
	border-radius: 0.2em;

	font-family: sans-serif;
	font-size: 1em;

	width: 56%;
	width: calc( 100% - ( 2% * 2 ) );

	height: 1.3em;

}

fieldset.info input:focus, fieldset.info textarea:focus {
	border-color: red;

}

fieldset.info input:hover + span.tip {
	display: block;

}

fieldset.info input:required, fieldset.info textarea:required, fieldset.info select:required { /*
	border-right-color: red;
	border-right-width: 0.5em; */

}

fieldset.info textarea {
	height: 5em;

}


fieldset.info div.row:first-of-type {
	border-top: 1px solid #122348;

}

fieldset.buttons {
	text-align: center;

	padding: 1.3em 20%;

	border: none;
	border-top: 2px solid #122348;;

}

fieldset.buttons button {
	border-radius: 0.2em;

	padding: 0.3em 1em 0.4em;
	margin: 0;

	color: white;
	border: none;

	float: left;

	width: 30%;

	text-shadow: 0 -1px 0 rgba( 0, 0, 0, 0.25 );

}

fieldset.buttons button[ type="submit" ] {
	background: linear-gradient( #7EB7DD, #2D7AAE );
	background-color: #2D7AAE;

	float: right;

}

fieldset.buttons button[ type="reset" ] {
	background: linear-gradient( #DD7C8D, #D80000 );
	background-color: #D80000;

}

fieldset.info legend {
	color: white;
	background-color: #122348;

	font-size: 1.2em;
	font-weight: bold;

	text-transform: uppercase;
	padding: 0.5em 4%;
/*
	border-top-left-radius: 0.5em;
	border-top-right-radius: 0.5em;
*/
	margin-bottom: 1.9em;

	width: 92%;

}

div.row {
	padding: 0.7em 2%;

}

div.row:nth-of-type( 2n ) {
	background-color: #DDDDDD;

}

div.row:hover {
	background-color: #FCDE9F;

}
