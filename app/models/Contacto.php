<?php

namespace LBBAS\app\models;

class Contacto {
	private int    $id;
	private int    $creador;
	private string $cliente;
	private array  $aliases;
	private array  $etiquetas;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $CLIENTE, array $ALIASES, array $ETIQUETAS) {
		$this->id 		 = $ID;
		$this->creador   = $CREADOR;
		$this->cliente   = $CLIENTE;
		$this->aliases   = &$ALIASES;
		$this->etiquetas = &$ETIQUETAS;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>