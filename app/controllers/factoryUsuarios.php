<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\models\Director as Director;
use LBBAS\app\models\Administrador as Administrador;
use LBBAS\app\models\Empleado as Empleado;

class factoryUsuarios {
	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	
	/// @Setters

	/// @Methods
	public function create(string $tipo, int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, bool $habilitado, int $fechaPago) {
		return match($tipo) {
			"Director" 		=> new 		Director($cedula, $nombre, $correo, $telefono, $celular, $clave, $habilitado, $fechaPago),
			"Administrador" => new Administrador($cedula, $nombre, $correo, $telefono, $celular, $clave, $habilitado, $fechaPago),
			"Empleado" 		=> new 		Empleado($cedula, $nombre, $correo, $telefono, $celular, $clave, $habilitado, $fechaPago),
			default => NULL
		};
	}
}

?>