<?php
/**
 * Usage:
 * 			$val = new Validator;
 * 			$val->addField('key in $_POST array', 'field name for error showing')
 * 				->addRule('require')->addRule('min:3');
 * 				OR
 * 			$val->add('username', 'Имя пользователя', 'require|min:3|max:16');
 * 				OR
 * 			$val->add('password')->addRules('require|min:6')->addErrMsg->('require', 'Обязательное поле');
 * 
 *  		if ($val->check($_POST)()) {
 *  		echo 'OKAY!';
 *  		//do some logic
 *  		} else {
 *  		//extracting errors
 *  		$errors = $val->getErrors(); // 1 first error as a string for every field
 *  			OR
 *  		$errors = $val->getAllErrors(); //all errors as an array for every field
 * 
 *  		extract($errors);
 *  		}
 */
namespace App\helpers;
use \App\core\Database\QueryBuilder;

class Validator {
	private $currentField = null,
	$errors = [],
	$fieldNames = [],
	$errMsg = [],
	$pass = null,
	$rules = [],
	$arrayForValidation;
	
	//database connecttion
	public function __construct($arrayForValidation) {
		$this->arrayForValidation = $arrayForValidation;
		$this->db = QueryBuilder::getInstance();
	}

	public function add($field, $fieldName = '', $rules = null) {
		//set field name
		$this->addField($field, $fieldName);
		if ($rules) {
			$this->addRules($rules);
		}
		return $this;
	}
	
	public function addField($field, $fieldName = '') {
		$this->currentField = $field;
		if (empty($fieldName)) {
			$this->fieldNames[$this->currentField] = $this->currentField;
		} else {
			$this->fieldNames[$this->currentField] = $fieldName;
		}
		return $this;
	}

	public function addRule($rule, $value = true) {
		$this->rules[$this->currentField][$rule] = $value;
		return $this;
	}

	public function addRules($rules) {
		//set rules
		foreach (explode('|', $rules) as $rules) {
			$rulesValues = explode(':', $rules);	
			if (!isset($rulesValues[1])) {
				$rulesValues[1] = true;
			}
			$this->addRule($rulesValues[0], $rulesValues[1]);
			//add feature to implement errMsg
		}
		return $this;
	}

	public function addErrMsg($rule, $message) {
		// 'match', 'Пароли не совпадают'
		$this->errMsg[$this->currentField][$rule] = $message;
		return $this;
	}

	public function check() {
		if (empty($this->arrayForValidation)) {
			throw new Exception('Array for validation is not passed!');
		}
		foreach ($this->rules as $field => $rules) {
			$this->currentField = $field;
			foreach ($rules as $this->rule => $value) {
				if (method_exists($this, $this->rule)) {
					//calling rule functions
					if (isset($this->arrayForValidation[$field])) {
						call_user_func([$this, $this->rule], $value);
					} else {
						throw new Error('Input array doesn\'t contain the ' . strtoupper($item) . ' key!');
					}
				} else {
					throw new Exception('Validator doesn\'t have the ' . strtoupper($rule) . ' rule!');
				}
			}
		}
		if (empty($this->errors)) {
			return true;
		}
		return false;
	}

	public function getAllErrors() {
		return $this->errors;
	}

	public function getErrors() {
		foreach ($this->errors as $field => $error) {
			$this->errors[$field] = array_values($this->errors[$field])[0];
		}
		return $this->errors;
	}

	/**
	 * Private functions below
	 */
	private function addError($error) {
		// $field = 'Error';
		if (isset($this->errMsg[$this->currentField][$this->rule])) {
			return $this->errors[$this->currentField][$this->rule] = $this->errMsg[$this->currentField][$this->rule];
		}
		return $this->errors[$this->currentField][$this->rule] = $error;
	}

	/**
	 * Rules function, returns true if item is passed and false in other case
	 * Input array is available as $this->arrayForValidation.
	 */
	

	private function require() {
		$item = $this->arrayForValidation[$this->currentField];
		if (empty($item)) {
			return $this->addError('Обязательное поле');
		}
	}

	private function max(int $value) {
		if (strlen($this->arrayForValidation[$this->currentField]) > $value) {
			return $this->addError("Поле должно быть меньше $value символов");
		}
	}

	private function min(int $value) {
		if (strlen($this->arrayForValidation[$this->currentField]) < $value) {
			return $this->addError("Поле должно быть больше $value символов");
		}
	}

	private function match($value) {
		if ($this->arrayForValidation[$this->currentField] != $this->arrayForValidation[$value]) {
			$this->errors[$value][$this->rule] = '';
			return $this->addError("Поля не совпадают");
		}
	}

	private function filter_username() {
		if (!preg_match("/^[a-zA-Z0-9]*$/", $this->arrayForValidation[$this->currentField])) {
			return $this->addError('Неправильное имя пользователя');
		}
	}

	private function filter_email() {
		if (!filter_var($this->arrayForValidation[$this->currentField], FILTER_VALIDATE_EMAIL)) {
			return $this->addError('Неправильный email');
		}
	}

	private function filter_nospace() {
		if (preg_match("/\\s/", $this->arrayForValidation[$this->currentField])) {
			return $this->addError('Поле не должно содержать пробелы');
		}
	}

	private function unique($table) {
		if (!empty(($this->db->get($table, [$this->currentField => $this->arrayForValidation[$this->currentField]])))) {
			if (isset($this->fieldNames[$this->currentField])) {
				$field = $this->fieldNames[$this->currentField];
			} else {
				$field = $this->currentField;
			}
			return $this->addError("{$field} уже используется");
		}
	}

	private function exists($table) {
		if (empty(($this->db->get($table, [$this->currentField => $this->arrayForValidation[$this->currentField]])))) {
			if (isset($this->fieldNames[$this->currentField])) {
				$field = $this->fieldNames[$this->currentField];
			} else {
				$field = $this->currentField;
			}
			return $this->addError("Такой {$field} не зарегистрирован");
		}
	}
}