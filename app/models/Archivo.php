<?php

namespace LBBAS\app\models;

class Archivo {
	private string $filepath;
	private int    $creador;
	private string $nombre;
	private int    $fechaCreacion;

	/// @Constructor
	public function __construct(int $PATH, int $CREADOR, string $NOMBRE, int $FECHA) {
		$this->filepath	= $PATH;
		$this->creador  = $CREADOR;
		$this->nombre   = $NOMBRE;
		$this->fecha    = $FECHA;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>