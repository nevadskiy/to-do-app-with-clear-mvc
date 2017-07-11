<?php

namespace App\helpers;

class Redirect {
	public static function to($path, $message = null, $status = 'success') {
		if ($message) {
			Alert::setFlash($message, $status);
		}
		if (headers_sent()) {
			echo "<script>window.location = '{$path}';</script>";
		} else {
			header("Location: {$path}");
		}
	}
}