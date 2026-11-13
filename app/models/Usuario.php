<?php

namespace LBBAS\app\models;

class Usuario {
	private int    $cedula;
	private string $nombre;
	private string $correo;
	private string $telefono;
	private string $celular;
	private string $clave;
	private bool   $habilitado;
	private int    $fechaPago;

	/// @Constructor
	public function __construct(string $CEDULA, string $NOMBRE, string $CORREO, string $TELEFONO, string $CELULAR, string $CLAVE, bool $HABIL, int $FECHAPAGO) {
		$this->cedula	  = $CEDULA;
		$this->nombre	  = $NOMBRE;
		$this->correo	  = $CORREO;
		$this->clave	  = $CLAVE;
		$this->habilitado = $HABIL;
		$this->fechaPago  = $FECHAPAGO;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters

	/// @Methods

}

?>