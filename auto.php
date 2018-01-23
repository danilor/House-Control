<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set( 'session.cookie_httponly', 1 );
error_reporting(E_ALL);
$classesDir = array (
	'classes/',
	'config/',
);

foreach ($classesDir as $directory) {
	$files = scandir( $directory );
	foreach( $files AS $file ){
		if( $file !== "." && $file !== ".." ){
			include( $directory . DIRECTORY_SEPARATOR . $file );
		}
	}
}

/**
GLOBAL HEADERS
*/

$isHttps = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off';
if ($isHttps){
  header('Strict-Transport-Security: max-age=31536000'); // FF 4 Chrome 4.0.211 Opera 12
}
header( "Set-Cookie: name=value; httpOnly" );
header('X-XSS-Protection: 1; mode=block');
header('X-Frame-Options: sameorigin');
header('X-Content-Type-Options: nosniff');
//header("Content-Security-Policy: default-src 'self'; script-src 'self' ;");
//header("X-Content-Security-Policy: default-src 'self'; script-src 'self';");