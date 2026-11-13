<?php

namespace LBBAS\app\models;

class Celular extends Contacto {
	private string $numeroCelular;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $CLIENTE, array $ALIASES, array $ETIQUETAS, string $CELULAR) {
		parent::__construct($ID, $CREADOR, $CLIENTE, $ALIASES, $ETIQUETAS);
		$this->numeroCelular = $CELULAR;
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