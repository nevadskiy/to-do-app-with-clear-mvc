<?php 

$GLOBALS['config'] = [
	'path' => [
		'views' => __DIR__ . '/app/views'
	],

	'database' => [
		'DB_TYPE' => 'mysql',
		'DB_NAME' => 'todo',
		'DB_HOST' => '127.0.0.1',
		'username' => 'root',
		'password' => ''
	],
	'cookie' => [
		'authTokenName' => 'auth',
		'authTokenExpire' => 60 * 60 * 24 * 7
	],
	'session' => [
		'userId' => 'id',
		'flashMessage' => 'flash'
	],
	'default' => [
		'controller' => 'task',
		'method' => 'index',
	],
	'pageTitles' => [
		'default' => 'To do application',
		'login' => 'Login',
		'register' => 'Register',
	],

	'routes' => [
		/**
		 * 1. redirect controller:
		 *		'uriController' => 'Controller'
		 *
		 * 2. redirect method:
		 * 		'Controller' => ['URI method' => 'controller method']
		 *   	or 
		 *   	'uriController' => 'Controller/method'
		 * 
		 * 3. set default method:
		 * 		'Controller' => [
		 * 			'default' => 'method'
		 * 		]
		 * 4. set method parameters:
		 * 		any pattern parameters like '[0-9]' => 'method'
		 */
		
		'login' => 'auth/login',
		'register' => 'auth/register',
		'logout' => 'auth/logout',
		'task' => [
			'[0-9]' => 'index',
		]
	]
];