<?php

namespace LBBAS\app\models;

class Producto {
	private string $uuid;
	private string $nombre;
	private string $descripcion;
	private int    $stock;
	private float  $precio;

	/// @Constructor
	public function __construct(string $UUID, string $NOMBRE, string $DESC, int $STOCK, float $PRECIO) {
		$this->uuid 	   = $UUID;
		$this->nombre	   = $NOMBRE;
		$this->descripcion = $DESC;
		$this->stock	   = $STOCK;
		$this->precio 	   = $PRECIO;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>