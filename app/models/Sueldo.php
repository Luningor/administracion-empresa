<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Archivo as Archivo;

class Sueldo extends Archivo {
	private int $recipiente;

	/// @Constructor
	public function __construct(string $PATH, int $CREADOR, string $NOMBRE, int $FECHA, mixed ...$ARGS) {
		if(func_num_args() !== 5) return NULL;
		parent::__construct($PATH, $CREADOR, $NOMBRE, $FECHA);

		$this->recipiente = func_get_arg(4);
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