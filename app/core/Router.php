<?php 

namespace App\core;
use \App\helpers\Config;

class Router {
	private $controller;
	private $method;
	private $routes;
	private $url;

	public function __construct() {
		$this->controller = Config::get('default/controller');
		$this->method =  Config::get('default/method');
		$this->routes = Config::get('routes');
		$this->url = self::parseUrl();

		//Setting controllerName
		if (isset($this->url[0])) {
			if (array_key_exists($this->url[0], $this->routes) && (!is_array($this->routes[$this->url[0]]))) {
				if (preg_match('~/~', $this->routes[$this->url[0]])) {
					$matches = explode('/', $this->routes[$this->url[0]]);
					$this->controller = $matches[0];
					$this->url[1] = $matches[1];
				} else {
					$this->controller = $this->routes[$this->url[0]];
				}
			} else {
				$this->controller = $this->url[0];
			}
			unset($this->url[0]);
		}

		//Setting methodName
		if (isset($this->url[1])) {
			//check whether is route to url method
			if ($this->method == Config::get('default/method')) {
				$this->method = $this->url[1];
			}
			if (array_key_exists($this->controller, $this->routes)) {
				foreach ($this->routes[$this->controller] as $pattern => $method) {
					//Delimeters should be ~ because of possible "/" in found string
					if ( preg_match("~$pattern~", $this->url[1]) ) {
						$this->method = $method;
						break;
					}
				}
			}
			unset($this->url[1]);
		} else if (array_key_exists($this->controller, $this->routes) && array_key_exists('default', $this->routes[$this->controller])) {
			$this->method = $this->routes[$this->controller]['default'];
		}

		//Setting right controller name and action name
		$this->controller = '\App\controllers\\' . ucfirst($this->controller) . 'Controller';
		$this->method .= 'Action';
		//Get a parameters	
		$this->parameters = $this->url ? array_values($this->url) : [];

		if (class_exists($this->controller)) {
			//Creating a OBJECT of controller
			$this->controller = new $this->controller();
		} else {
			self::page404();
		}
		if (method_exists($this->controller, $this->method)) {
			return call_user_func_array([$this->controller, $this->method], $this->parameters);	
		} else {
			return self::page404();
		}
	}

	public function parseUrl() {
		if (isset($_GET['url'])) {
			$url = $_GET['url'];
			unset($_GET['url']);
			return explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
		}
	}
	
	public function page404() {
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		require_once (__DIR__ . '/../views/404.php');
		die();
	}
}