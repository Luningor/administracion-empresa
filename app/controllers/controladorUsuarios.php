<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\core\logger as logger;
use LBBAS\app\controllers\factoryUsuarios as factoryUsuarios;
use LBBAS\app\models\Usuario as Usuario;
use LBBAS\app\repositories\repo as repo;

class controladorUsuarios {
	static private controladorUsuarios $instance;
	private array					   $usuarios;
	private factoryUsuarios			   $factory;

	/// @Constructor
	public function __construct() {
		$this->factory = new factoryUsuarios();
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() : controladorUsuarios {
		controladorUsuarios::$instance = controladorUsuarios::$instance ?? new controladorUsuarios();
		return controladorUsuarios::$instance;
	}

	public function getUsuarioCedula(int $cedula) : Usuario {
		if(isset($this->usuarios[$cedula])) return $this->usuarios[$cedula];
		else {
			// Get from database
			$queryResult = repo::get_usuario_cedula($cedula);
			var_dump($queryResult);
			var_dump($queryResult["telefono"]);
			$this->usuarios[$cedula] = $this->factory->create(
				($queryResult["isDirector"] == true ? "Director" : ($queryResult["isAdministrator"] == true ? "Administrador" : ($queryResult["isEmployee"] == true ? "Empleado" : NULL))),
				$queryResult["cedula"], $queryResult["nombre"], 
				$queryResult["correo"], $queryResult["telefono"], $queryResult["celular"], 
				$queryResult["clave"], $queryResult["habilitado"], strtotime($queryResult["fechaPago"])
			);

			return $this->usuarios[$cedula];
		}
	} 

	/// @Setters

	/// @Methods
	public function crearDirector(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, bool $habilitado, int $fechaPago) : bool {
		// Query against database. Should return true on success since query is acting as a proxy to mysqli
		if(repo::registrar_director($cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado)) {
			$tipo = "Director";
			$this->usuarios[$cedula] = $this->factory->create($tipo, $cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado);
			return true;
		}
		
		// Should throw an exception before this so it should never reach
		// but for the sake of redundancy:
		return false;
	}
	
	public function crearAdministrador(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, bool $habilitado, int $fechaPago) : bool {
		if(repo::registrar_administrador($cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado)) {
			$tipo = "Administrador";
			$this->usuarios[$cedula] = $this->factory->create($tipo, $cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado);
			return true;
		}
		
		return false;
	}
	
	public function crearEmpleado(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, bool $habilitado, int $fechaPago) : bool {
		if(repo::registrar_empleado($cedula, $nombre, $correo, $telefono, $celular, $clave, $habilitado, $fechaPago)) {
			$tipo = "Empleado";
			$this->usuarios[$cedula] = $this->factory->create($tipo, $cedula, $nombre, $correo, $telefono, $celular, $clave, $habilitado, $fechaPago);
			return true;
		}
		
		return false;
	}
	
}

?>