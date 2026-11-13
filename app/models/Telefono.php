<?php

namespace LBBAS\app\models;

class Telefono extends Contacto {
	private string $numeroTelefono;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $CLIENTE, array $ALIASES, array $ETIQUETAS, string $TELEFONO) {
		parent::__construct($ID, $CREADOR, $CLIENTE, $ALIASES, $ETIQUETAS);
		$this->numeroTelefono = $TELEFONO;
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