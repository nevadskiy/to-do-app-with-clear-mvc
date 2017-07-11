<?php

namespace App\helpers;

class Token {
	public static function generate($name) {
		return Session::put($name, md5(uniqid()));
	}
	public static function check($name, $token) {
		if (Session::exists($name) && $token === Session::get($name)) {
			Session::delete($name);
			return true;
		}
		return false;
	}
}