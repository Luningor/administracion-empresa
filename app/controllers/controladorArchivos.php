<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\controllers\factoryArchivos as factoryArchivos;
use LBBAS\app\models\Archivo as Archivo;
use LBBAS\app\repositories\repo as repo;

class controladorArchivos {
	static private controladorArchivos $instance;
	private array 					   $archivos = [];
	private factoryArchivos			   $factory;

	/// @Constructor
	public function __construct() {
		$this->factory = new factoryArchivos();
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() : controladorArchivos {
		controladorArchivos::$instance = controladorArchivos::$instance ?? new controladorArchivos();
		return controladorArchivos::$instance;
	}

	public function getArchivoFilePath(string $path) : ?Archivo {
		if(isset($this->archivos[$path])) return $this->archivos[$path];
		else {
			// Get from database
			$queryResult = repo::get_file_from_path($path)[0];

			// No user with that id
			if(is_null($queryResult)) {
				throw new \Exception("FAILED_TO_GET_FILE_NO_FILE_WITH_PATH");
				return NULL;
			}

			$tipo = (
				$queryResult["isPurchase"] == true ? "Compra" : (
				$queryResult["isSale"] == true ? "Venta" : (
				$queryResult["isDocument"] == true ? "Documento" : (
				$queryResult["isPayment"] ? "Sueldo" : NULL
			))));

			if(is_null($tipo)) {
				throw new \Exception("FAILED_TO_GET_FILE_INVALID_FILE_TYPE");
				return NULL;
			}

			switch($tipo) {
				case "Compra":
					$this->archivos[$path] = $this->factory->create(
						$tipo,
						$queryResult["filePath"], $queryResult["creador"], $queryResult["nombre"], $queryResult["fechaCreacion"],
						$queryResult["compradoPor"]
					);
					break;

				case "Venta":
					$this->archivos[$path] = $this->factory->create(
						$tipo,
						$queryResult["filePath"], $queryResult["creador"], $queryResult["nombre"], $queryResult["fechaCreacion"]
					);
					break;

				case "Documento":
					$this->archivos[$path] = $this->factory->create(
						$tipo,
						$queryResult["filePath"], $queryResult["creador"], $queryResult["nombre"], $queryResult["fechaCreacion"],
						$queryResult["descripcion"], $queryResult["visible"]
					);
					break;

				case "Sueldo":
					$this->archivos[$path] = $this->factory->create(
						$tipo,
						$queryResult["filePath"], $queryResult["creador"], $queryResult["nombre"], $queryResult["fechaCreacion"],
						$queryResult["recipiente"]
					);
					break;
			}

			return $this->archivos[$path];
		}
	}

	/// @Setters

	/// @Methods
	public function crearArchivoCompra(string $path, int $creador, string $nombre, int $fechaCreacion, int $comprador) : bool {
		if(repo::registrar_archivo_compra($path, $creador, $nombre, $fechaCreacion, $comprador)) {
			$this->archivos["path"] = $this->factory->create("Compra", $path, $creador, $nombre, $fechaCreacion, $comprador);
			return true;
		}

		return false;
	}

	public function  crearArchivoVenta(string $path, int $creador, string $nombre, int $fechaCreacion) : bool {
		if(repo::registrar_archivo_venta($path, $creador, $nombre, $fechaCreacion)) {
			$this->archivos["path"] = $this->factory->create("Venta", $path, $creador, $nombre, $fechaCreacion);
			return true;
		}

		return false;
	}

	public function 	crearDocumento(string $path, int $creador, string $nombre, int $fechaCreacion, string $descripcion, bool $visible) : bool {
		if(repo::registrar_documento($path, $creador, $nombre, $fechaCreacion, $descripcion, $visible)) {
			$this->archivos["path"] = $this->factory->create("Documento", $path, $creador, $nombre, $fechaCreacion, $descripcion, $visible);
			return true;
		}

		return false;
	}

	public function 	   crearSueldo(string $path, int $creador, string $nombre, int $fechaCreacion, int $recipiente) : bool {
		if(repo::registrar_sueldo($path, $creador, $nombre, $fechaCreacion, $recipiente)) {
			$this->archivos["path"] = $this->factory->create("Sueldo", $path, $creador, $nombre, $fechaCreacion, $recipiente);
			return true;
		}

		return false;
	}
	
}

?>