<?php

namespace LBBAS\app\models;

class ArchivoVenta extends Archivo {
	/// @Constructor
	public function __construct(int $PATH, int $CREADOR, string $NOMBRE, int $FECHA) {
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