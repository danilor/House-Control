<?php

global $key;
function getControlUrl(){
	global $key;
	return Common::getRootFolderUrl() . 'control.php?key=' . @$key;
}

if( !isset($_GET["key"]) ){
	@session_start();
	$key = session_id() . Common::randomKey( 32 );
	header( "Location:index.php?key=" . $key );
}
/**
Lets clean the key
*/

$key = 		$_GET["key"];
//$key = 		strip_tags( $key );
//$key = 		htmlentities( $key );
$key = 		preg_replace('/[^a-zA-Z0-9]/', '', $key);
