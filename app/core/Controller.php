<?php

namespace App\core;

class Controller {
	function __construct() {
		$this->view = new View();
	}
}