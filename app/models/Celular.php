<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Contacto as Contacto;
use LBBAS\app\models\Usuario as Usuario;

class Celular extends Contacto {
	private string $numeroCelular;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $CLIENTE, array $ALIASES, array $ETIQUETAS, string $CELULAR) {
		parent::__construct($ID, $CREADOR, $CLIENTE, $ALIASES, $ETIQUETAS);
		$this->numeroCelular = $CELULAR;
	}

	/// @Destructor
	public function __destruct() {
		parent::__destruct();
	}

	/// @Getters

	/// @Setters
	public function setDireccionContacto(string $direccion) : bool {
		if(empty($direccion)) return false;
		if(!Celular::checkNumeroIntegrity($direccion)) return false;
	
		$this->numeroCelular = $direccion;
	}

	/// @Methods
	// Same format as user numbers to keep consistency
	public static function checkNumeroIntegrity(string $numero) : bool {
		return Usuario::checkNumeroIntegrity($numero);
	}
}

?>