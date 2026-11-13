<?php

namespace LBBAS\app\models;

class Cliente {
	private string $alias;
	private string $nombre;
	private string $informacion;

	/// @Constructor
	public function __construct(string $ALIAS, string $NOMBRE, string $INFO,) {
		$this->alias 	   = $ALIAS;
		$this->nombre	   = $NOMBRE;
		$this->informacion = $INFO;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>