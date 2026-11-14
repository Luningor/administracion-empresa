<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\controllers\controladorConexion as controladorConexion;

class controladorClientes {
	static private controladorClientes $instance;
	private array 					   $clientes;

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorClientes::$instance = controladorClientes::$instance ?? new controladorClientes();
		return controladorClientes::$instance;
	}

	/// @Setters

	/// @Methods
	
}

?>