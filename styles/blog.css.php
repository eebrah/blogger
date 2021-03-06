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
		background-color: inherit;

		width: 100%;
		max-width: 1000px;
		
		margin: 2em auto;

	}

	div.header {
		min-height: 10em;
		background-color: red;
	
		border-radius: 0.2em;
		
		margin: 1em 0;
	
	}

	div.footer {
		clear: both;
	
	}

	div.body {
		width: 100%;

	}

		div.sideColumn {
			width: 26%;

			background-color: #122348;
			color: white;

			float: right;
			
			margin-left: 2%;
			padding: 1em 3%;
			
			border-radius: 0.2em;
			
		}

		div.mainColumn {
			width: 60%;

			background-color: white;
			color: black;

			float: right;
			
			padding: 2em 3%;
			
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
		
		p {
			margin: 1em 0;
			line-height: 1.5em;
		
		}
		
		.mainColumn .comments {
			padding: 1em 0;
		
		}
		
		.mainColumn .comment {
			border: 1px dotted #999999;
			padding: 0;
			
			margin: 0.5em 0;
			
			border-radius: 0.2em;
		
		}
		
		.mainColumn .comment p {
			margin: 0.5em 0;
			padding: 0 1em;
		
		}
		
		.mainColumn .comment p.meta {
			background-color: #E5E5FF;
			padding: 0.33em 1em;
			margin: 0;
		
		}
		
		.mainColumn .commentForm {
			box-shadow: 0 0 0.5em #BBBBBB;
		
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

fieldset.info  label {
	font-size: 1em;
	font-weight: bold;
/*
	text-transform: uppercase; */

	margin: 0.5em 2%;

	float: left;
	text-align: right;
	width: 30%;
	
	display: none;

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
	font-size: 0.9em;

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

	padding: 1.3em 5%;

	border: none;
	border-top: 2px solid #122348;;

}

fieldset.buttons button {
	border-radius: 0.2em;

	padding: 0.3em 1.2em 0.4em;
	margin: 0;

	color: white;
	border: none;

	float: left;

	font-weight: bold;

	text-shadow: 0 -1px 0 rgba( 0, 0, 0, 0.25 );
	text-transform: capitalize;

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

	font-size: 1em;
	font-weight: bold;

	text-transform: uppercase;
	padding: 0.5em 4%;

	border-top-left-radius: 0.2em;
	border-top-right-radius: 0.2em;

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

@media (max-device-width: 480px) {


	div.wrapper {
		width: 100%;
		max-width: 100%;
	
		margin: 0;

	}
	
	div.header {
		margin: 0;
		border-radius: 0;
	
	}

	div.sideColumn, div.mainColumn {

		width: 94%;
		
		margin: 0;

		display: block;
		clear: both;
		
		border-radius: 0;

	}

}

