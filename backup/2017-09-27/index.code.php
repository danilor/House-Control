<?php

function getControlUrl(){
	return Common::getRootFolderUrl() . 'control.php?key=' . @$_GET["key"];
}

if( !isset($_GET["key"]) ){
	@session_start();
	$key = session_id() . Common::randomKey( 32 );
	header( "Location:index.php?key=" . $key );
}