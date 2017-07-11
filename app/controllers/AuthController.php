<?php

namespace App\controllers;
use \App\core\Controller;
use \App\models\User;
use \App\helpers\Redirect;
use \App\helpers\Input;
use \App\helpers\Config;
use \App\helpers\Validator;

class AuthController extends Controller {
	private $user;
	public function __construct() {
		parent::__construct();
		$this->user = new User;
	}

	public function loginAction() {
		if ($this->user->isLoggedIn()) {
			Redirect::to('/', 'You are already logged in', 'danger');
		}
		if (Input::get('login')) {
			$val = new Validator($_POST);
			$val->add('username', 'логин', 'require|min:4|max:16|filter_username|exists:users');
			$val->add('password', 'Пароль')->addRules('require');
			if ($val->check()) {
				if ($this->user->login(Input::get('username', true), Input::get('password'), Input::get('remember'))) {
					Redirect::to('/');
				} else {
					$this->view->data('errors', ['password' => 'Неверный пароль']);
				}
			} else {
				$this->view->data('errors', $val->getErrors());
			}
		}
		return $this->view->render('main', 'auth/login');
	}

	public function registerAction() {
		if ($this->user->isLoggedIn()) {
			Redirect::to('/', 'You are already logged in', 'danger');
		}
		if (Input::exists('register')) {
			$val = new Validator($_POST);
			$val->add('username', 'Логин', 'require|min:4|max:16|filter_username|unique:users');
			$val->add('password', 'Пароль')->addRules('require|min:6|max:16');
			$val->addField('repassword')->addRule('match' , 'password')->addRule('require')->addErrMsg('match' , 'Пароли не совпадают');

			if ($val->check()) {
				$this->user->register(Input::get('username', true), Input::get('password'));
				Redirect::to('/login', 'You registered successfully');
			} else {
				$this->view->data('errors', $val->getErrors());
			}
		}
		return $this->view->render('main', 'auth/register');
	}

	public function logoutAction() {
		if ($this->user->isLoggedIn()) {
			$this->user->logout();
			Redirect::to('/', 'You are logged out!');
		} else {
			Redirect::to('/', 'You are not even logged in', 'danger');
		}
	}
}