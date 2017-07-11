<?php

namespace App\helpers;

class Config {
	public static function get($path) {
		$config = $GLOBALS['config'];
		$path = explode('/', $path);
		foreach ($path as $bit) {
			if (isset($config[$bit])) {
				$config = $config[$bit];
			}
		}
		return $config;
	}
}