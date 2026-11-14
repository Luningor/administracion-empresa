<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\controllers\controladorConexion as controladorConexion;

class controladorContactos {
	static private controladorContactos $instance;
	private array 						$contactos;

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorContactos::$instance = controladorContactos::$instance ?? new controladorContactos();
		return controladorContactos::$instance;
	}

	/// @Setters

	/// @Methods
	
}

?>