<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\controllers\controladorConexion as controladorConexion;

class controladorUsuarios {
	static private controladorUsuarios $instance;
	private array					   $usuarios;

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorUsuarios::$instance = controladorUsuarios::$instance ?? new controladorUsuarios();
		return controladorUsuarios::$instance;
	}

	/// @Setters

	/// @Methods
	
}

?>