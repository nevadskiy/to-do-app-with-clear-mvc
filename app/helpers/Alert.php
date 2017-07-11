<?php 

namespace App\helpers;

class Alert {
	//possible statuses: success (default), info, warning, danger
	//styled in twitter bootstrap
	private static function style($message, $status = 'success') {
		return '<div class="alert alert-' . $status . '">
		<strong> ' . ucfirst($status) . '! </strong>' . $message . '</div>';
	}
	//flash messages
	public static function setFlash($message, $style = null) {
		if ($style) {
			$message = self::style($message, $style);
		}
		return Session::set(Config::get('session/flashMessage'), $message);
	}
	public static function getFlash() {
		$sessionName = Config::get('session/flashMessage');
		if (Session::exists($sessionName)) {
			$session = Session::get($sessionName);
			Session::delete($sessionName);
			return $session;
		}
		return '';
	}
}