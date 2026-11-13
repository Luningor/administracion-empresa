<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;

class controladorConexion {
	static private ?controladorConexion $instance = NULL;
	private ?\mysqli					$connection;

	/// @Constructor
	public function __construct() {
		global $_CONFIG;
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
			global $_CONFIG;
			try { $this->connection = new \mysqli($_CONFIG['DB_CONN_SRV'], $_CONFIG['DB_CONN_USR'], $_CONFIG['DB_CONN_PWD'], $_CONFIG['DB_CONN_DBN']); }
			catch(\mysqli_sql_exception $e) {
				$this->connection = NULL;
				logger::get()->log_error($e->getMessage());
			
				throw new \Exception("CONNECTION TO DATABASE FAILED");
			}
		}

		try {
			$result = $this->connection->query($statement);
			return $result;
		}
		catch(\mysqli_sql_exception $e) {
			// Query failed
			logger::get()->log_error($e->getMessage());

			throw new \Exception("QUERY FAILED");
		}
	}

	// Execute static query
	// Throws an error on failure
	public function query(string $statement, string $types, mixed ...$args) {
		if(is_null($this->connection)) {
			global $_CONFIG;
			try { $this->connection = new \mysqli($_CONFIG['DB_CONN_SRV'], $_CONFIG['DB_CONN_USR'], $_CONFIG['DB_CONN_PWD'], $_CONFIG['DB_CONN_DBN']); }
			catch(\mysqli_sql_exception $e) {
				$this->connection = NULL;
				logger::get()->log_error($e->getMessage());
			
				throw new \Exception("CONNECTION TO DATABASE FAILED");
			}
		}

		try { $stmt = $this->connection->prepare($statement); }
		catch(mysqli_sql_exception $e) {
			logger::get()->log_error($e->getMessage());

			var_dump($stmt);
			var_dump(logger::get()->get_log_errors());

			throw new \Exception("STATEMENT PREPARATION FAILED");
		}

		try { $stmt->bind_param($types, ...$args); }
		catch(Exception $e) {
			logger::get()->log_error($e->getMessage());
			throw new \Exception("ARGUMENT BINDING FAILED");
		}

		try {
			$result = $stmt->execute();
			return $result;
		}
		catch (\mysqli_sql_exception $e) {
			// Query failed
			logger::get()->log_error($e->getMessage());
			throw new \Exception("QUERY FAILED");
		}
	}
}

?>