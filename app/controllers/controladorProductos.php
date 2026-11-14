<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\controllers\controladorConexion as controladorConexion;

class controladorProductos {
	static private controladorProductos $instance;
	private array						$productos;

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorProductos::$instance = controladorProductos::$instance ?? new controladorProductos();
		return controladorProductos::$instance;
	}

	/// @Setters

	/// @Methods
	
}

?>