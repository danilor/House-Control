<?php
chdir("../");
require_once("auto.php");


function getHouseControlFile( $key ){
	return CONTROL_FOLDER . DIRECTORY_SEPARATOR . HOUSE_CONTROL_FOLDER . DIRECTORY_SEPARATOR . $key;
}
function getHouseInstructionsFile( $key ){
	return CONTROL_FOLDER . DIRECTORY_SEPARATOR . HOUSE_INSTRUCTIONS_FOLDER . DIRECTORY_SEPARATOR . $key;
}

function storeKey( $key ){
	file_put_contents( getHouseControlFile( $key ) , $key );
	//	$this->pdo = new \PDO("sqlite:" . DATABASE_DIR);
}

function checkHouseKey( $key ){
	if( file_exists( getHouseControlFile( $key ) ) ){
		$filetime = filemtime( getHouseControlFile( $key ) );
		$now   = time();
		$timed = $now - filemtime(getHouseControlFile( $key ));
		if ( $timed >= DISCONECTION_TIME ) { // 10 seconds
			return 0;
		}else{
			return 1;
		}
	}else{
		return 0;
	}
}

function readInstruction( $key ){
	$file = getHouseInstructionsFile( $key );
	if( file_exists( $file ) ){
		$f = fopen($file, 'r');
		$line = fgets($f);
		fclose($f); // You close because you only want the first one.
		$newDoc = file_get_contents($file, true);
		$newFileContents = substr( $line, strpos($newDoc, "\n")+1 );
		file_put_contents($file, $newFileContents);
		return $line;
	}else{
		return "";
	}
}

function addInstruction( $key , $code ){
	$code = $code . PHP_EOL;
	file_put_contents( getHouseInstructionsFile( $key ) , $code , FILE_APPEND );
	echo 1;
}

function sendEmailWithLink( $key ){
	$post = $_POST;
	if( isset( $post["control_url"] ) && trim( $post["control_url"] ) !== "" && isset( $post["email"] ) && trim( $post["email"] ) !== "" ){
		$url = trim( $post["control_url"] );
		$email = trim( $post["email"] );
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: " . EMAIL_FROM . "\r\n";
			$message = '<p>Click in the following link to access the remote control APP: <a href="' . $url. '">' . $url . '</a></p>';
			return mail($email,EMAIL_SUBJET, $message , $headers );
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}

if( isset($_GET["store_key"]) && trim($_GET["store_key"]) !== "" ){
	storeKey( trim($_GET["store_key"]) );
}

if( isset($_GET["check_house"] )  && trim( $_GET["check_house"] ) !== "" ){
	$key = $_GET["check_house"];
	echo checkHouseKey( $key );
}

if( isset( $_POST["add_instruction"] ) && trim( $_POST["add_instruction"] ) !== "" ){
	$key = $_GET["key"];
	$code = $_POST["add_instruction"];
	addInstruction( $key , $code );
}

if( isset($_GET["read_instructions"] )  && trim( $_GET["read_instructions"] ) !== "" ){
	$key = $_GET["read_instructions"];
	echo readInstruction( $key );
}

if( isset($_GET["send_email"] )  && trim( $_GET["send_email"] ) !== "" ){
	$key = $_GET["send_email"];
	echo sendEmailWithLink( $key );
}