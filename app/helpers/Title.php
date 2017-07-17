<?php

namespace App\helpers;
use \App\helpers\Config;

class Title {
	public static function get() {
		$url = $_SERVER['REQUEST_URI'];
		$controllerName = explode('/', $url)[1];
		$titles = Config::get('pageTitles');
		if (array_key_exists($controllerName, $titles)) {
			return $titles[$controllerName];
		}
		return Config::get('pageTitles/default');
	}
}