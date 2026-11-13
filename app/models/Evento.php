<?php

namespace LBBAS\app\models;

class Evento {
	private int    $id;
	private int    $creador;
	private string $descripcion;
	private int    $fecha;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $DESC, int $FECHA) {
		$this->id 		   = $ID;
		$this->creador 	   = $CREADOR;
		$this->descripcion = $DESC;
		$this->fecha 	   = $FECHA;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>