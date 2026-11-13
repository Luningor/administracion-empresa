<?php

namespace LBBAS\app\models;

class Sueldo extends Archivo {
	private int $recipiente;

	/// @Constructor
	public function __construct(int $PATH, int $CREADOR, string $NOMBRE, int $FECHA, int $RECIPIENTE) {
		parent::__construct($PATH, $CREADOR, $NOMBRE, $FECHA);
		$this->recipiente = $RECIPIENTE;
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