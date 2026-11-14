<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\controllers\controladorConexion as controladorConexion;

class controladorArchivos {
	static private controladorArchivos $instance;
	private array 					   $archivos = [];

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorArchivos::$instance = controladorArchivos::$instance ?? new controladorArchivos();
		return controladorArchivos::$instance;
	}

	/// @Setters

	/// @Methods
	
}

?>