<?php

namespace App\models;
use \App\core\Model;
use \App\helpers\Session;
use \App\helpers\Config;
use \App\helpers\Cookie;
use \App\helpers\Hash;

class User extends Model {
	private $isLoggedIn = false,
	$data = null;
	
	public function __construct() {
		parent::__construct();

		if (Session::exists(Config::get('session/userId'))) {
			$user = Session::get(Config::get('session/userId'));
			if ($this->get($user)) {
				$this->isLoggedIn = true;
			} else {
				$this->logout();
			}
		}
	}

	public function get($user) {
		$field = (is_numeric($user)) ? 'id' : 'username';
		$data = $this->db->get('users', [$field => $user]);

		if (!empty($data)) {
			$this->data = $data;
			return true;
		}
		return false;
	}

	public function isLoggedIn() {
		return $this->isLoggedIn;
	}
	public function data() {
		return $this->data;
	}

	public function login($username, $password, $remember = false) {
		$user = $this->get($username);
		if ($this->data()->password == Hash::make($password)) {
			Session::set(Config::get('session/userId'), $this->data()->id);

			//remember me option
			if ($remember) {
				$hashCheck = $this->db->get('users_sessions', ['user_id' => $this->data()->id]);
				if (empty($hashCheck)) {
					$hash = Hash::unique();
					$this->db->insert('users_sessions', [
						'user_id' => $this->data()->id,
						'auth_token' => $hash
						]);
				} else {
					$hash = $hashCheck->auth_token;
				}
				Cookie::set(Config::get('cookie/authTokenName'), $hash, Config::get('cookie/authTokenExpire'));
			}
			return true;
		}
		return false;
	}

	public function register($username, $password) {
		$password = Hash::make($password);
		if (!$this->db->insert('users', ['username' => $username, 'password' => $password])) {
			throw new Exception('Account was not created.');
		}
	}
	public function logout() {
		if (Cookie::exists(Config::get('cookie/authTokenName'))) {
			$this->db->delete('users_sessions', ['user_id' => Session::get(Config::get('session/userId'))]);
			Cookie::delete(Config::get('cookie/authTokenName'));
		}
		Session::delete(Config::get('session/userId'));
		return true;
	}
}