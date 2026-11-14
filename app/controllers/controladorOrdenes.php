<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\controllers\controladorConexion as controladorConexion;

class controladorOrdenes {
	static private controladorOrdenes $instance;
	private array					  $ordenes;

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorEventos::$instance = controladorEventos::$instance ?? new controladorEventos();
		return controladorEventos::$instance;
	}

	/// @Setters

	/// @Methods
	
}

?>