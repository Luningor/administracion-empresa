<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Cliente as Cliente;
use LBBAS\app\repositories\repo as repo;

class controladorClientes {
	static private controladorClientes $instance;
	private array 					   $clientes;

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorClientes::$instance = controladorClientes::$instance ?? new controladorClientes();
		return controladorClientes::$instance;
	}

	public function getClienteAlias(string $alias) : Cliente {
		if(isset($this->clientes[$alias])) return $this->clientes[$alias];
		else {
			// Get from database
			$queryResult = repo::get_cliente_alias($alias);

			// No client with that alias
			if(is_null($queryResult))
				throw new \Exception("FAILED_TO_GET_CLIENT_NO_CLIENT_WITH_ALIAS");

			$this->clientes[$alias] = new Cliente(
				$alias, $queryResult["nombre"], $queryResult["informacion"]
			);

			return $this->clientes[$alias];
		}
	}

	/// @Setters

	/// @Methods
	public function crearCliente(string $alias, string $nombre, string $informacion) : bool {
		if(repo::registrar_cliente($alias, $nombre, $informacion)) {
			$this->clientes[$alias] = new Cliente($alias, $nombre, $informacion);
			return true;
		}

		return false;
	}

	public function actualizarNombreCliente(string $alias, string $nombre) : bool {
		if(repo::actualizar_nombre_cliente($alias, $nombre)) {
			$this->getClienteAlias($alias)->setNombre($nombre);
			return true;
		}

		return false;
	}

	public function actualizarInformacionCliente(string $alias, string $informacion) : bool {
		if(repo::actualizar_info_cliente($alias, $informacion)) {
			$this->getClienteAlias($alias)->setInformacion($informacion);
			return true;
		}

		return false;
	}

	public function modificarCliente(string $alias, string $nombre, string $informacion) : bool {
		if(repo::modificar_cliente($alias, $nombre, $informacion) && repo::actualizar_info_cliente($alias, $informacion)) {
			$cliente = $this->getClienteAlias($alias);

			$cliente->setNombre($nombre);
			$cliente->setInformacion($informacion);
			return true;
		}

		return false;
	}

	public function buscarCliente(string $text) : array {
		// Search in name and ask for contact aliases for contain matches
		// Return user data in array of users
	}
}

?>