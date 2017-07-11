<?php

namespace App\helpers;

class Hash {
	//return 64 length hash string
	public static function make($string, $salt = '') {
		return hash('sha256', $string . $salt);
	}
	public static function makeByLen($length = 16) {
		return bin2hex(random_bytes($length/2));
	}
	public static function unique() {
		return self::make(uniqid()); 
	}
}