<?php

namespace LBBAS\app\models;

class Documento extends Archivo {
	private string $descripcion;
	private bool   $visible;

	/// @Constructor
	public function __construct(int $PATH, int $CREADOR, string $NOMBRE, int $FECHA, string $DESC, bool $VISIBLE) {
		parent::__construct($PATH, $CREADOR, $NOMBRE, $FECHA);
		$this->descripcion = $DESC;
		$this->visible	   = $VISIBLE;
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