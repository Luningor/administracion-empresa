<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Archivo as Archivo;

class ArchivoVenta extends Archivo {
	/// @Constructor
	public function __construct(string $PATH, int $CREADOR, string $NOMBRE, int $FECHA, mixed ...$ARGS) {
		if(func_num_args() !== 4) return NULL;
		parent::__construct($PATH, $CREADOR, $NOMBRE, $FECHA);
	}

	/// @Destructor
	public function __destruct() {
		parent::__destruct();
	}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>