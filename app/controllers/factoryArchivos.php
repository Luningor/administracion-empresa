<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Archivo	   as Archivo;
use LBBAS\app\models\ArchivoCompra as Compra;
use LBBAS\app\models\ArchivoVenta  as Venta;
use LBBAS\app\models\Documento 	   as Documento;
use LBBAS\app\models\Sueldo		   as Sueldo;

class factoryArchivos {
	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	
	/// @Setters

	/// @Methods
	public function create(string $tipo, string $path, int $creador, string $nombre, int $fechaCreacion, mixed ...$args) : ?Archivo {
		return match($tipo) {
			"Compra" 	=> new 	  Compra($path, $creador, $nombre, $fechaCreacion, ...$args),
			"Venta" 	=> new 	   Venta($path, $creador, $nombre, $fechaCreacion, ...$args),
			"Documento" => new Documento($path, $creador, $nombre, $fechaCreacion, ...$args),
			"Sueldo" 	=> new 	  Sueldo($path, $creador, $nombre, $fechaCreacion, ...$args),
			default => NULL
		};
	}	
}

?>