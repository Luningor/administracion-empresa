<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\config\config as config;

class controladorConexion {
	static private controladorConexion $instance;
	private ?\mysqli				   $connection;

	/// @Constructor
	public function __construct() {
		// Load configs
		global $_CONFIG;
		config::get();

		try { $this->connection = new \mysqli($_CONFIG['DB_CONN_SRV'], $_CONFIG['DB_CONN_USR'], $_CONFIG['DB_CONN_PWD'], $_CONFIG['DB_CONN_DBN']); }
		
		// Connection failed, handled later when new query is requested.
		catch (\mysqli_sql_exception $e) {
			$this->connection = NULL;
			logger::get()->log_error($e->getMessage());
		}
	}

	/// @Destructor
	public function __destruct() {
		if(!is_null($this->connection)) $this->connection->close();
	}

	/// @Getters
	public static function get() : controladorConexion {
		controladorConexion::$instance = controladorConexion::$instance ?? new controladorConexion();
		return controladorConexion::$instance;
	}

	/// @Methods
	// Execute static query
	// Throws an error on failure
	public function simple_query(string $statement) {
		if(is_null($this->connection)) {
			// Load configs
			global $_CONFIG;
			config::get();

			try { $this->connection = new \mysqli($_CONFIG['DB_CONN_SRV'], $_CONFIG['DB_CONN_USR'], $_CONFIG['DB_CONN_PWD'], $_CONFIG['DB_CONN_DBN']); }
			catch(\mysqli_sql_exception $e) {
				$this->connection = NULL;
				logger::get()->log_error($e->getMessage());
			
				throw new \Exception("CONNECTION_TO_DATABASE_FAILED");
				return false;
			}
		}

		try {
			$result = $this->connection->query($statement);
			return $result;
		}
		catch(\mysqli_sql_exception $e) {
			// Query failed
			logger::get()->log_error($e->getMessage());

			throw new \Exception("QUERY_FAILED");
			return false;
		}
	}

	// Execute parameterized query
	// Throws an error on failure
	public function query(string $statement, string $types, mixed ...$args) {
		// Check connection integrity
		if(is_null($this->connection)) {
			// Load configs
			global $_CONFIG;
			config::get();
			
			try { $this->connection = new \mysqli($_CONFIG['DB_CONN_SRV'], $_CONFIG['DB_CONN_USR'], $_CONFIG['DB_CONN_PWD'], $_CONFIG['DB_CONN_DBN']); }
			catch(\mysqli_sql_exception $e) {
				$this->connection = NULL;
				logger::get()->log_error($e->getMessage());
			
				throw new \Exception("CONNECTION_TO_DATABASE_FAILED");
				return false;
			}
		}

		// Prepare statement
		try { $stmt = $this->connection->prepare($statement); }
		catch(\mysqli_sql_exception $e) {
			logger::get()->log_error($e->getMessage());
			throw new \Exception("STATEMENT_PREPARATION_FAILED");
			return false;
		}

		// Bind parameters
		try { $stmt->bind_param($types, ...$args); }
		catch(\mysqli_sql_exception $e) {
			logger::get()->log_error($e->getMessage());
			throw new \Exception("ARGUMENT_BINDING_FAILED");
			return false;
		}

		// Execute
		try { return $stmt->execute(); }
		catch (\mysqli_sql_exception $e) {
			// Query failed
			logger::get()->log_error($e->getMessage());
			throw new \Exception("QUERY_FAILED");
			return false;
		}
	}

	// Execute parameterized query that returns values
	// Throws an error on failure
	public function fetch_query(string $statement, string $types, mixed ...$args) {
		// Check connection integrity
		if(is_null($this->connection)) {
			// Load configs
			global $_CONFIG;
			config::get();
			
			try { $this->connection = new \mysqli($_CONFIG['DB_CONN_SRV'], $_CONFIG['DB_CONN_USR'], $_CONFIG['DB_CONN_PWD'], $_CONFIG['DB_CONN_DBN']); }
			catch(\mysqli_sql_exception $e) {
				$this->connection = NULL;
				logger::get()->log_error($e->getMessage());
			
				throw new \Exception("CONNECTION_TO_DATABASE_FAILED");
				return false;
			}
		}

		// Prepare statement
		try { $stmt = $this->connection->prepare($statement); }
		catch(\mysqli_sql_exception $e) {
			logger::get()->log_error($e->getMessage());
			throw new \Exception("STATEMENT_PREPARATION_FAILED");
			return false;
		}

		// Bind parameters
		try { $stmt->bind_param($types, ...$args); }
		catch(\mysqli_sql_exception $e) {
			logger::get()->log_error($e->getMessage());
			throw new \Exception("ARGUMENT_BINDING_FAILED");
			return false;
		}

		// Execute
		try { $stmt->execute(); }
		catch (\mysqli_sql_exception $e) {
			// Query failed
			logger::get()->log_error($e->getMessage());
			throw new \Exception("QUERY_FAILED");
			return false;
		}

		// Get result
		try { $result = $stmt->get_result(); } 
		catch (\mysqli_sql_exception $e) {
			logger::get()->log_error($e->getMessage());
			throw new \Exception("FAILED_WHILE_GETTING_QUERY_RESULT");
			return false;
		}

		// Get associated rows
		try {
			$rows = [];
			do {
				$check = $result->fetch_assoc();
				if(!is_null($check)) $rows[] = $check;
			} 
			while(!is_null($check));
			
			return $rows; 
		} 
		catch (\mysqli_sql_exception $e) {
			logger::get()->log_error($e->getMessage());
			throw new \Exception("FAILED_TO_FETCH_RESULT");
			return false;
		}
			
	}
}

?>