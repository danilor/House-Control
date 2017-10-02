<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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