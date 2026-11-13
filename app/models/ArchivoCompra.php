<?php

namespace LBBAS\app\models;

class ArchivoCompra extends Archivo {
	private $compradoPor;

	/// @Constructor
	public function __construct(int $PATH, int $CREADOR, string $NOMBRE, int $FECHA, int $COMPRADOR) {
		parent::__construct($PATH, $CREADOR, $NOMBRE, $FECHA);
		$this->compradoPor = $COMPRADOR;
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