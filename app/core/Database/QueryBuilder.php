<?php

/**
 * Singleton: instance - QueryBuilder::getInstance();
 */

/**
 * public methods for using outside:
 * 	- get('table', ['field' => value, 'field' => 'value']) [second parameter (assoc array) is not required!] [returns arrays of data]
 * 	- getAll('table', ['field' => value]) [second parameter (assoc array) is not required!] [returns arrays of data]
 * 	- insert('table', ['field' => value]) [returns true or false]
 * 	- delete('table', ['field' => value]) [returns lastInsertedId or false]
 * 	- update('table', ['field' => value]) [returns true or false]
 *
 * 	- get('users')->featQuery('LIMIT 10');
 * 	- sql('SELECT * FROM posts INNER JOIN comments');
 */

namespace App\core\Database;

class QueryBuilder {
	public $db;
	private static $_instance = null;

	public static function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		$this->db = new Connector;
	}
	
	private function __clone() {
		//denied cloning
	}

	//private methods below
	private function query($sql, $values = []) {
		//filtering keys from values array because sql view looks like WHERE `field` = ? AND ...
		// die(var_dump($sql));
		
		if (isset($this->featQuery)) {
			$sql .= ' ' . $this->featQuery;
			$this->featQuery = null;
		}

		if ($query = $this->db->prepare($sql)) {
			if (!empty($values)) {
				$values = array_values($values);	
				if (count($values)) {
					$x = 1;
					foreach ($values as $param) {
						$query->bindValue($x, $param);
						$x++;
					}
				}
			}
			if ($query->execute()) {
				$queryWord = explode(' ', $sql)[0];
				if ($queryWord == 'SELECT') {
					if ($query->rowCount()) {
						return $query->fetchAll(\PDO::FETCH_OBJ);
					}
				} else if ($queryWord == 'INSERT') {
					return $this->db->lastInsertId();
				} else {
					return true;
				}
			}
		}
		return false;
	}

	private function prequery($action, $table, $parameters = []) {
		if ($parameters) {	
			//return all keys that is not numeric(needed bcuz of update function realize)
			$fieldsArray = array_filter(array_keys($parameters), function($k) {return !is_int($k);});

			$valuesArray = array_values($parameters);

			$where = [];
			foreach($fieldsArray as $field) {
				$where[] = "{$field} = ?";
			}

			$where = implode(' AND ', $where);
		}
		$act = explode(' ', $action)[0];
		//so sql below looks loke 'SELECT * FROM users WHERE id = ? AND email = ?'	
		if ($act == 'SELECT' || $act == 'DELETE') {
			$sql = "{$action} FROM {$table}";
		} else if ($act == 'UPDATE') {
			$sql = "{$action}";
		}
		if (isset($where)) {
			$sql .= " WHERE {$where}";
		}
		if (isset($valuesArray)) {
			return $this->query($sql, $valuesArray);	
		} else {
			return $this->query($sql);	
		}			
	}

	/**
	 * PUBLIC FUNCTION BELOW
	 */
	public function sql($sql) {
		$query = $this->db->prepare($sql);
		$query->execute();
		if (explode(' ', $sql)[0] == 'SELECT') {
			if ($query->rowCount()) {
				return $query->fetchAll(\PDO::FETCH_OBJ);
			}
		} else {
			return true;
		}
	}

	public function featQuery($feat) {
		$this->featQuery = $feat;
		return $this;
	}

	public  function get($table, $parameters = null) {
		if ($table) {
			return $this->prequery('SELECT *', $table, $parameters)[0];
		}
		return false;
	}

	public function getAll($table, $parameters = null) {
		if ($table) {
			return $this->prequery('SELECT *', $table, $parameters);
		}
		return false;
	}

	public function delete($table, $parameters) {
		if ($table && $parameters) {
			return $this->prequery('DELETE', $table, $parameters);
		}
		return false;
	}

	public function update($table, $set, $parameters) {
		if ($table && $set && $parameters) {
			$setFields = [];
			$setValues = [];
			foreach ($set as $field => $value) {
				$setFields[] = "{$field} = ?";
				$setValues[] = $value;
			}
			$parameters = array_merge($setValues, $parameters);

			$set = implode(', ', $setFields);
			
			$action = "UPDATE {$table} SET {$set}";
			
			return $this->prequery($action, $table, $parameters);
		}
	}

	public function insert($table, $fields = []) {
		if ($table && $fields) {
			$fieldsArray = array_keys($fields);

			//builds VALUES part of sql query
			for ($i = 0, $max = count($fieldsArray), $values = []; $i < $max; $i++) {
				$values[] = '?';
			}
			$values = implode(', ', $values);

			//ex: INSERT INTO users (id, username) VALUES (?, ?)
			$sql = "INSERT INTO {$table} (" . implode(', ', $fieldsArray) . ") VALUES ({$values})";
			return $this->query($sql, $fields);
		}
		return false;
	}
}