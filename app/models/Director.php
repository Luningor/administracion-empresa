<?php

namespace LBBAS\app\models;

class Director extends Usuario {

	/// @Constructor
	public function __construct(string $CEDULA, string $NOMBRE, string $CORREO, string $TELEFONO, string $CELULAR, string $CLAVE, bool $HABIL, int $FECHAPAGO) {
		parent::__construct($CEDULA, $NOMBRE, $CORREO, $TELEFONO, $CELULAR, $CLAVE, $HABIL, $FECHAPAGO);
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