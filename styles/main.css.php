<?php header( 'Content-type: text/css' ); if( substr_count( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) ) { ob_start( "ob_gzhandler" ); } else { ob_start(); } ?>

* {
	padding: 0;
	margin: 0;

}

body {
	background-color: white;

	font-family: sans-serif;

}

	ul {
		list-style: none;

	}

	div.wrapper {
		background-color: white;

		width: 100%;

	}

	div.header {}

	div.body {
		width: 100%;

	}

		div.sideColumn {
			width: 20em;

			background-color: #122348;
			color: white;

			float: left;
			position: fixed;

			height: 100%;
			
			left: -17em;
			
			transition: left 1s;

		}
		
		div.sideColumn:hover {
			left: 0;
		
		}

		div.sideColumn a {
			color: white;
			text-decoration: none;

		}

		div.sideColumn ul {
			padding: 1em;

		}

		div.sideColumn ul li {
			margin: 0 1em;

			padding: 0.2em 0;

		}

		div.sideColumn ul li ul {
			padding: 0.2em 0;

		}

		div.sideColumn ul li a {}

		div.mainColumn {
			min-width: 640px;
			width: calc( 100% - (  ( 2 * 4em ) ) );

			min-height: 20em;
			background-color: white;

			float: left;

			padding: 2em;

			margin-left: 3em;

		}

		h1, h2, h3 {
			color: #122348;

			border-bottom: 1px dotted #122348;

		}

		div.mainColumn p {
			line-height: 1.5em;

			margin: 0.5em 0;

		}

		table {
			border-collapse: collapse;

			border-top: 1px solid #666666;
			border-bottom: 1px solid #666666;

			margin: 2em 0;

		}

		table a {
			text-decoration: none;
			color: #333333;

		}

		table td, table th {
			border: none;

		}

		table thead th {
			padding: 0.5em 1em;

			text-transform: uppercase;

			background-color: #122348;
			color: white;

		}

		table tbody tr:nth-of-type( 2n ) {
			background-color: #DDDDDD;

		}

		table tbody tr:hover {
			background-color: #FCDE9F;

		}

		table tbody td, table tbody th {
			padding: 0.4em 1em;

		}

		table tbody th {
			text-align: right;

		}
		
		ul.actions li {
			display: inline;
		
		}

	div.dialog {

		max-width: 600px;

		margin: 2em auto;

	}

form {
	font-size: 0.85em;

	box-shadow: 0.5em 0.5em 1em #999999;

}

div.dialog fieldset {
/*	border: 1px solid #122348; */

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
	width: calc( 100% - ( 30% + ( 2% * 4  ) ) );

}

fieldset.info input, fieldset.info textarea {
	padding: 0.4em 2%;

	border: 1px solid #999999;

	font-family: sans-serif;
	font-size: 1em;

	width: 56%;
	width: calc( 100% - ( 30% + ( 2% * 6  ) ) );

	height: 1.3em;

}

fieldset.info input:focus, fieldset.info textarea:focus {
	border-color: red;

}

fieldset.info input:hover + span.tip {
	display: block;

}

fieldset.info input:required, fieldset.info textarea:required, fieldset.info select:required {
	border-right-color: red;
	border-right-width: 0.5em;

}

fieldset.info textarea {
	height: 5em;

}


fieldset.info div.row:first-of-type {
	margin-top: -2.3em;
	border-top: 1px solid #122348;

}

fieldset.buttons {
	text-align: center;

	padding: 1.3em 20%;

	border: none;
	border-top: 2px solid #122348;;

}

fieldset.buttons button {
	border-radius: 0.4em;

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

	div.ticket {
		margin: 1em 0;
		border: 1px dotted #333333;

	}

	div.ticket .metadata {
		background-color: #DDDDDD;

		padding: 0.5em 1em;

		margin: 0;

	}

	div.ticket p {
		padding: 0 1em;

	}

	div.footer {
		clear: both;

	}
