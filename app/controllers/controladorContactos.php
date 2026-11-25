<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\controllers\factoryContactos as factoryContactos;
use LBBAS\app\models\Contacto as Contacto;
use LBBAS\app\repositories\repo as repo;

class controladorContactos {
	static private controladorContactos $instance;
	private array 						$contactos;
	private factoryContactos 			$factory;

	/// @Constructor
	public function __construct() {
		$this->factory = new factoryContactos();
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() : controladorContactos {
		controladorContactos::$instance = controladorContactos::$instance ?? new controladorContactos();
		return controladorContactos::$instance;
	}

	public function getContactoId(int $id) : ?Contacto {
		if(isset($this->contactos[$id])) return $this->contactos[$id];
		else {
			// Get from database
			$queryResult = repo::get_contacto_id($id)[0];

			// No contact with that id
			if(is_null($queryResult)) {
				throw new \Exception("FAILED_TO_GET_CONTACT_NO_CONTACT_WITH_ID");
				return NULL;
			}

			$contact_type = (
				 $queryResult["isMail"] == true ? "Mail" : 
				($queryResult["isPhoneNumber"] == true ? "Telefono" : 
				($queryResult["isCellphoneNumber"] == true ? "Celular" : NULL
			)));

			$creador = $queryResult["creador"];
			$cliente = $queryResult["cliente"];

			$aliases = repo::get_aliases_contacto_id($id);
			$etiquetas = repo::get_etiquetas_contacto_id($id);

			$this->contactos[$id] = $this->factory->create(
				$contact_type, $id, $creador, $direccion, $cliente, $aliases, $etiquetas
			);

			return $this->contactos[$id];
		}
	}

	public function getContactosAlias(string $alias) {
		$contactosResult = repo::get_contactos_alias($alias);

		if(!empty($contactosResult)) foreach($contactosResult as $result) {
			$contact_type = (
				 $result["isMail"] == true ? "Mail" : 
				($result["isPhoneNumber"] == true ? "Telefono" : 
				($result["isCellphoneNumber"] == true ? "Celular" : NULL
			)));

			$creador = $result["creador"];
			$cliente = $result["cliente"];
			$direccion = $result["direccion"];

			$aliases = repo::get_aliases_contacto_id($result["identificador"]);
			$etiquetas = repo::get_etiquetas_contacto_id($result["identificador"]);

			$this->contactos[$result["identificador"]] = $this->factory->create(
				$contact_type, $result["identificador"], $creador, $direccion, $cliente, $aliases, $etiquetas
			);
		}
	}

	/// @Setters

	/// @Methods
	public function crearMail(int $creador, string $mail, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : bool {
		$id = repo::registrar_mail($creador, $mail, $cliente, $aliases, $tags);

		if(!is_null($id)) {
			$tipo = "Mail";
			$this->contactos[$id] = $this->factory->create($tipo, $id, $creador, $mail, $cliente, $aliases, $tags);
			return true;
		}

		return false;
	}

	public function crearTelefono(int $creador, string $telefono, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : bool {
		$id = repo::registrar_mail($creador, $mail, $cliente, $aliases, $tags);

		if(!is_null($id)) {
			$tipo = "Telefono";
			$this->contactos[$id] = $this->factory->create($tipo, $id, $creador, $telefono, $cliente, $aliases, $tags);
			return true;
		}

		return false;
	}

	public function crearCelular(int $creador, string $celular, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : bool {
		$id = repo::registrar_mail($creador, $mail, $cliente, $aliases, $tags);

		if(!is_null($id)) {
			$tipo = "Celular";
			$this->contactos[$id] = $this->factory->create($tipo, $id, $creador, $celular, $cliente, $aliases, $tags);
			return true;
		}

		return false;
	}

	public function asignarClienteContactoId(int $id, string $cliente) : bool {
		if(repo::asignar_cliente_contacto_id($id, $cliente))
			return $this->getContactoId($id)->setCliente($cliente);

		return false;
	}

	public function asignarAliasContactoId(int $id, string $alias) : bool {
		if(repo::asignar_alias_contacto_id($id, $alias))
			return $this->getContactoId($id)->setCliente($alias);

		return false;
	}

	public function asignarEtiquetaContactoId(int $id, string $etiqueta) : bool {
		if(repo::asignar_etiqueta_contacto_id($id, $etiqueta))
			return $this->getContactoId($id)->setCliente($etiqueta);

		return false;
	}

	
	public function eliminarContactoId(int $id) : bool {
		if(repo::eliminar_contacto_id($id)) {
			if(isset($this->contactos[$id])) 
				unset($this->contactos[$id]);
			return true;
		}

		return false;
	}
	
	public function eliminarAliasContactoId(int $id, string $alias) : bool {
		if(repo::eliminar_alias_contacto_id($id, $alias))
			return $this->getContactoId($id)->removeAlias($alias);
	}
	
	public function eliminarEtiquetaContactoId(int $id, string $etiqueta) : bool {
		if(repo::eliminar_etiqueta_contacto_id($id, $etiqueta)) 
			return $this->getContactoId($id)->removeEtiqueta($etiqueta);
	}
}

?>