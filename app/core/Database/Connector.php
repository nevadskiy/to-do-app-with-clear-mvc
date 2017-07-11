<?php 

namespace App\core\Database;
use \App\helpers\Config;

class Connector extends \PDO {

	public function __construct() {

		$dsn = 	Config::get('database/DB_TYPE') . ":" . 
		"host=" . Config::get('database/DB_HOST') .	";" . 
		"dbname=" . Config::get('database/DB_NAME');
		$username = Config::get('database/username');
		$password = Config::get('database/password');

		try {
			parent::__construct($dsn, $username, $password);
			$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			echo $e->getMessage();
			exit();
		}
	}
	private function __clone() {
		//denied cloning
	}
}

?>