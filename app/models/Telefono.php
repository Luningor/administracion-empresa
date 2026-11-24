<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Contacto as Contacto;
use LBBAS\app\models\Usuario as Usuario;

class Telefono extends Contacto {
	private string $numeroTelefono;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $CLIENTE, array $ALIASES, array $ETIQUETAS, string $TELEFONO) {
		parent::__construct($ID, $CREADOR, $CLIENTE, $ALIASES, $ETIQUETAS);
		$this->numeroTelefono = $TELEFONO;
	}

	/// @Destructor
	public function __destruct() {
		parent::__destruct();
	}

	/// @Getters

	/// @Setters
	public function setDireccionContacto(string $direccion) : bool {
		if(empty($direccion)) return false;
		if(!Telefono::checkNumeroIntegrity($direccion)) return false;
	
		$this->numeroTelefono = $direccion;
	}

	/// @Methods
	// Same format as user numbers to keep consistency
	public static function checkNumeroIntegrity(string $numero) : bool {
		return Usuario::checkNumeroIntegrity($numero);
	}
}

?>