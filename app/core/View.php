<?php

namespace App\core;
use \App\helpers\Config;

class View {
	public function render($template = null, $contentPage, $data = null) {
		//extracting $this->data
		if (!empty($this->data)) {
			extract($this->data);
		}
		//extracting $data
		if (is_array($data)) {
			extract($data);
		}
		//include template
		if ($template) {
			require_once Config::get('path/views') . '/templates/' . $template . '.php';	
		} else {
			require_once Config::get('path/views') . '/' . $contentPage . '.php';	
		}
	}
	public function data($key, $data) {
		return $this->data[$key] = $data;
	}

	public static function page404() {
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		require_once (__DIR__ . '/../views/404.php');
		die();
	}
}