<?php

namespace LBBAS\app\models;

class Cliente {
	private string $alias;
	private string $nombre;
	private string $informacion;

	/// @Constructor
	public function __construct(string $ALIAS, string $NOMBRE, string $INFO) {
		$this->alias 	   = $ALIAS;
		$this->nombre	   = $NOMBRE;
		$this->informacion = $INFO;
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters
	public function setNombre(string $nombre) : bool {
		if(empty($nombre)) return false;
		if(!Cliente::checkNombreIntegrity($nombre)) return false;

		$this->nombre = $nombre;
		return true;
	}
	
	public function setInformacion(string $informacion) : bool {
		$this->informacion = $informacion;
		return true;
	}


	/// @Methods
	static public function checkAliasIntegrity(string $alias) : bool {
		// Only letters, (._-) between letters, at least 8 characters long
		return preg_match("/^(?=.*[a-zA-Z]{1,})(?=.[\.\_\-]*)(?!^[\.\_\-])(?!.*[\.\_\-]$).{8,}/", $alias);
	}
	
	static public function checkNombreIntegrity(string $nombre) : bool {
		// Only letters with points and spaces inbetween, at least 4 characters long
		return preg_match("/^(?=^[a-zA-z].*[a-zA-Z]*$)(?=.*[ \.]*)(?!^[ \.].*)(?!.*[ \.]$).{4,}$/", $nombre);
	}
}

?>