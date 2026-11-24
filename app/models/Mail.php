<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Contacto as Contacto;
use LBBAS\app\models\Usuario as Usuario;

class Mail extends Contacto {
	private string $direccionEmail;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $CLIENTE, array $ALIASES, array $ETIQUETAS, string $MAIL) {
		parent::__construct($ID, $CREADOR, $CLIENTE, $ALIASES, $ETIQUETAS);
		$this->direccionEmail = $MAIL;
	}

	/// @Destructor
	public function __destruct() {
		parent::__destruct();
	}

	/// @Getters

	/// @Setters
	public function setDireccionContacto(string $direccion) : bool {
		if(empty($direccion)) return false;
		if(!Mail::checkMailIntegrity($direccion)) return false;
	
		$this->direccionEmail = $direccion;
	}

	/// @Methods
	// Same format as user mails to keep consistency
	public static function checkNumeroIntegrity(string $mail) : bool {
		return Usuario::checkMailIntegrity($mail);
	}
}

?>