<?php

namespace LBBAS\app\models;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Cliente as Cliente;

class Contacto {
	private int    $id;
	private int    $creador;
	private string $cliente;
	private array  $aliases;
	private array  $etiquetas;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, ?string $CLIENTE = NULL, ?array $ALIASES = NULL, ?array $ETIQUETAS = NULL) {
		$this->id 		 =  $ID;
		$this->creador   =  $CREADOR;
		$this->cliente   =  $CLIENTE;
		$this->aliases   = &$ALIASES ?? [];
		$this->etiquetas = &$ETIQUETAS ?? [];
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters
	public function setCliente(string $cliente) : bool {
		if(!checkClienteIntegrity($cliente)) return false;

		$this->cliente = $cliente;
		return true;
	}

	/// @Methods
	public static function checkClienteIntegrity(string $cliente) : bool {
		return Cliente::checkAliasIntegrity($cliente);
	}
	
	public static function checkAliasIntegrity(string $alias) : bool {
		// Same format as client names to keep consistency
		return Cliente::checkNombreIntegrity($alias);
	}
	
	public static function checkAliasesIntegrity(array $aliases) : bool {
		if(empty($aliases)) return false;

		foreach($aliases as $alias) {
			$check = Contacto::checkAliasIntegrity($alias);
			if(!$check) break;
		}

		return $check;
	}
	
	public static function checkEtiquetaIntegrity(string $etiqueta) : bool {
		// Starts and ends with a letter, spaces in the middle, at least two characters long
		return preg_match("/(?=^[a-zA-Z].*[a-zA-Z]$)(?=.*[\ ]*.*).{2,}/", $etiqueta);
	}

	public static function checkEtiquetasIntegrity(array $etiquetas) : bool {
		if(empty($etiquetas)) return false;

		foreach($etiquetas as $etiqueta) {
			$check = Contacto::checkEtiquetaIntegrity($etiqueta);
			if(!$check) break;
		}

		return $check;
	}

	public function addAlias(string $alias) : bool {
		if(empty($alias)) return false;
		if(!Contacto::checkAliasIntegrity($alias)) return false;

		$this->aliases[$alias] = true;
		return true;
	}

	public function addEtiqueta(string $etiqueta) : bool {
		if(empty($etiqueta)) return false;
		if(!Contacto::checkEtiquetaIntegrity($etiqueta)) return false;

		$this->etiquetas[$etiqueta] = true;
		return true;
	}

	public function removeAlias(string $alias) : bool {
		if(!Contacto::checkAliasIntegrity($alias)) return false;

		if(isset($this->aliases[$alias])) 
			unset($this->aliases[$alias]);

		return true;
	}

	public function removeEtiqueta(string $etiqueta) : bool {
		if(!Contacto::checkAliasIntegrity($etiqueta)) return false;

		if(isset($this->etiquetas[$etiqueta])) 
			unset($this->etiquetas[$etiqueta]);

		return true;
	}
}

?>