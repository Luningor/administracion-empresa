<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Usuario as Usuario;

class Archivo {
	private string $filepath;
	private int    $creador;
	private string $nombre;
	private int    $fechaCreacion;

	/// @Constructor
	public function __construct(string $PATH, int $CREADOR, string $NOMBRE, int $FECHA) {
		$this->filepath 	 = $PATH;
		$this->creador		 = $CREADOR;
		$this->nombre		 = $NOMBRE;
		$this->fechaCreacion = $FECHA;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods
	public static function checkFilePathIntegrity(string $path) {
		// Valid filepath starting with <letter>:\, containing only letters, spaces, and [.-_()], length of at least 6
		return preg_match_all("/^[a-zA-Z]\:\\\\(?=[a-zA-Z0-9\\\\ñÑ\.\_\-\(\)]*)[a-zA-Z0-9\\\\ñÑ\.\_\-\(\)]*\.[a-zA-Z0-9]*$/", $path);
	}

	public static function checkNombreIntegrity(string $name) {
		// Valid name containing at least 6 letters, begins with a letter and can have spaces and [.-_]
		return preg_match("/(?=^[a-zA-Z0-9]([a-zA-Z0-9]|[ñÑ \.\-\_])*$).{6,}/", $name);
	}

	public static function checkFechaIntegrity(string $fecha) {
		return $fecha > 0;
	}

}

?>