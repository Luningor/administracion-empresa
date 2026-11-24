<?php

namespace LBBAS\app\models;

class Orden {
	private int    $numeroOrden;
	private string $comprador;
	private string $recibo;
	private array  $lineaOrden;

	/// @Constructor
	public function __construct(int $NUM, string $COMPRADOR, string $RECIBO) {
		$this->numeroOrden = $NUM;
		$this->comprador   = $NOMBRE;
		$this->recibo	   = $DESC;
		$this->lineaOrden = [];
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods
	public function insertarLineaOrden(int $uuid, int $cantidad) {
		$lineaOrden[] = [
			"uuid" => $uuid,
			"cant" => $cantidad
		];
	}

}

?>