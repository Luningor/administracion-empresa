<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Archivo as Archivo;

class Documento extends Archivo {
	private string $descripcion;
	private bool   $visible;

	/// @Constructor
	public function __construct(string $PATH, int $CREADOR, string $NOMBRE, int $FECHA, mixed ...$ARGS) {
		if(func_num_args() !== 6) return NULL;
		parent::__construct($PATH, $CREADOR, $NOMBRE, $FECHA);

		$this->descripcion = func_get_arg(4);
		$this->visible	   = func_get_arg(5);
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