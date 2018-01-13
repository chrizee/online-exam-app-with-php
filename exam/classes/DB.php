<?php
class DB {
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;

	private function __construct() {
		//connect to the database
		try {
			$this->_pdo = new PDO('mysql:host='.config::get('mysql/host').';dbname='.config::get('mysql/db'),config::get('mysql/username'),config::get('mysql/password'));	
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() {
		if(!isset(self::$_instance)) { 
		//checks if instance is set and sets it if not
		//makes connection to db to be done once in __construct
		//creates an instance of the DB class
			self::$_instance = new DB();
		}
		//returns an instance of the connection ie object 
		return self::$_instance;
	}

	//method used to run queries and bind values
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql) ) {
			$x = 1;
			if(count($params)) {
				foreach($params as $param) {
					$this->_query->bindValue($x,$param);
					$x++;
				}
			}
			// to avoid iterating over the array above and binding values, pass in the array of values you want to bind to execute()
			// if($this->_query->execute($params))
			if($this->_query->execute()) {
				//$this->_query->setFetchMode(PDO::FETCH_OBJ);
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
				print_r($this->_query->errorInfo());
			}
		}
		return $this;
	}

	//prototype method for mysql actions 
	public  function action($action, $table, $where = array()) {
		if(count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=', '<>', '<=>', '!=', 'LIKE');

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this->query($sql,array($value))->error()) {
					return $this;
				}
			}	
		}
		if(count($where) === 6) {
			$operators = array('=', '>', '<', '>=', '<=', '<>', '<=>', '!=', 'LIKE');

			$field1 		= $where[0];
			$operator1 	    = $where[1];
			$value1 		= $where[2];
			$field2 		= $where[3];
			$operator2 		= $where[4];
			$value2			= $where[5];

			if(in_array($operator1, $operators) && in_array($operator2, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field1} {$operator1} ? AND {$field2} {$operator2} ?";
				if(!$this->query($sql,array($value1, $value2))->error()) {
					return $this;
				}
			}	
		}
		return false;
	}

	public function get($table, $where, $fields = '*') {
		return $this->action("SELECT {$fields}", $table, $where);
	}

	public function delete($table, $where) {
		return $this->action("DELETE", $table, $where);
	}

	//method for inserting values into tables specified in the parameter
	public function insert($table, $fields = array()) {		
		$keys = array_keys($fields);
		$values = '';
		$x = 1;

		foreach($fields as $field) {
			$values .= '?';
			if($x < count($fields)) {
				$values .= ', ';
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys). "`) VALUES ({$values})";
		
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		
		return false;
	}

	//methos for updating values in table specified in the parameter
	public function update($table, $id, $fields) {
		$set ='';
		$x = 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}
		
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;		
	}

	public function updateTest($table, $id, $fields) {	//used cos id is named 'test_id'
		$set ='';
		$x = 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}
		
		$sql = "UPDATE {$table} SET {$set} WHERE test_id = {$id}";
		
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;		
	}

	public function transaction() {

	}

	public function createTable($tableName = array(), $fields = array(), $primary, $foreign, $references) {
		$sql = "CREATE TABLE IF NOT EXISTS ? ( ";
		foreach ($fields as $key => $value) {
			$sql .= "{$key} {$value},";
		}
		$sql .= "PRIMARY KEY( {$primary} ),";
		if($foreign && $references) {
			$sql .= "CONSTRAINT fk FOREIGN KEY ( {$foreign} ) REFERENCES {$references} )";
		} else {
			$sql .= ")";
		}

		if(!$this->query($sql, $tableName)->error())	{
			return true;
		}
		return false;
	}	

	public function createDb($dbName = array()) {
		$sql = "CREATE DATABASE IF NOT EXISTS ?";
		
		if(!$this->query($sql, $dbName)->error()) {
			return true;
		}
		return false;
	}

	public function results() {
		return $this->_results; 
	}

	public function first() {
		return $this->results()[0];
	}

	public function error() {
		return $this->_error;
	} 

	public function count() {
		return $this->_count;
	}	
}
?>