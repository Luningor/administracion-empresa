<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Contacto as Contacto;
use LBBAS\app\models\Mail as Mail;
use LBBAS\app\models\Telefono as Telefono;
use LBBAS\app\models\Celular as Celular;

class factoryContactos {
	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	
	/// @Setters

	/// @Methods
	public function create(string $tipo, int $id, int $creador, string $direccion, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : ?Contacto {
		return match($tipo) {
			"Mail" 	   => new	  Mail($id, $creador, $cliente, $aliases, $tags, $direccion),
			"Telefono" => new Telefono($id, $creador, $cliente, $aliases, $tags, $direccion),
			"Celular"  => new  Celular($id, $creador, $cliente, $aliases, $tags, $direccion),
			default => NULL
		};
	}
	
}

?>