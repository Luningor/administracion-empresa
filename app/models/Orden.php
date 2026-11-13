<?php

namespace LBBAS\app\models;

class Orden {
	private int    $numeroOrden;
	private string $comprador;
	private string $recibo;

	/// @Constructor
	public function __construct(int $NUM, string $COMPRADOR, string $RECIBO) {
		$this->numeroOrden = $NUM;
		$this->comprador   = $NOMBRE;
		$this->recibo	   = $DESC;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>