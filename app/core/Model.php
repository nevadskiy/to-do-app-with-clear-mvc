<?php

namespace App\core;
use \App\core\Database\QueryBuilder;
use \App\helpers\Session;
use \App\helpers\Cookie;
use \App\helpers\Config;

class Model {
	public $db;

	public function __construct() {
		$this->db = QueryBuilder::getInstance();
		$this->checkCookieToken();
	}

	public function checkCookieToken() {
		if (Cookie::exists(Config::get('cookie/authTokenName')) && !Session::exists(Config::get('session/userId'))) {
			$hash = Cookie::get(Config::get('cookie/authTokenName'));
			$hashCheck = $this->db->get('users_sessions', ['auth_token' => $hash]);

			if (!empty($hashCheck)) {
				Session::set(Config::get('session/userId'), $hashCheck->user_id);
			}
		}
	}
}