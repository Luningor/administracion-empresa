<?php

namespace LBBAS\app\models;

class Mail extends Contacto {
	private string $direccionEmail;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $CLIENTE, array $ALIASES, array $ETIQUETAS, string $MAIL) {
		parent::__construct($ID, $CREADOR, $CLIENTE, $ALIASES, $ETIQUETAS);
		$this->direccionEmail = $MAIL;
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