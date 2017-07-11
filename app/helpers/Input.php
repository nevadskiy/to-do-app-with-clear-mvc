<?php

namespace App\helpers;

class Input {
	public static function exists($name = null) {
		if (!empty($name)) {
			return (!empty($_POST[$name])) ? true : false;
		} else {
			return (!empty($_POST)) ? true : false;
		}	
	}
	public static function get($item, $escape = false) {
		if(isset($_POST[$item]) && $escape) {
			return self::escape($_POST[$item]);
		} else if (isset($_POST[$item])) {
			return $_POST[$item];
		}
		return '';
	}
	public static function escape($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}