<?php
class Common {
	public static function randomKey( $length ) {
		$key = '';
		$pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
		for($i=0; $i < $length; $i++) {
			$key .= $pool[mt_rand(0, count($pool) - 1)];
		}
		return $key;
	}

	public static function getRootFolderUrl(){
		$url = $_SERVER['REQUEST_URI']; //returns the current URL
		$parts = explode('/',$url);
		$dir = $_SERVER['SERVER_NAME'];
		for ($i = 0; $i < count($parts) - 1; $i++) {
			$dir .= $parts[$i] . "/";
		}
		$dir = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $dir;
		return $dir;
	}
}