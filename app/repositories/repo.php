<?php

namespace LBBAS\app\repositories;
use LBBAS\app\controllers\controladorConexion as controladorConexion;
use LBBAS\app\models\Usuario  as Usuario;
use LBBAS\app\models\Cliente  as Cliente;
use LBBAS\app\models\Producto as Producto;
use LBBAS\app\models\Contacto as Contacto;
use LBBAS\app\models\Archivo  as Archivo;

class repo {
	/// @Consultas relacionadas a usuarios
		private static function check_valid_user_info(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, int $fechaPago) : bool {
			if(empty($cedula)) {
				throw new \Exception("FAILED_TO_CREATE_USER_MISSING_UID");
				return false;
			}
			
			if(empty($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_USER_MISSING_NAME");
				return false;
			}
			
			if(!Usuario::checkNombreIntegrity($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_USER_INVALID_NAME");
				return false;
			}

			if(empty($correo) && empty($telefono) && empty($celular)) {
				throw new \Exception("FAILED_TO_CREATE_USER_NO_CONTACT_INFO");
				return false;
			}
			
			if(!empty($correo) && !Usuario::checkCorreoIntegrity($correo)) {
				throw new \Exception("FAILED_TO_CREATE_USER_INVALID_MAIL_FORMAT");
				return false;
			}
			
			if(!empty($telefono) && !Usuario::checkNumeroIntegrity($telefono)) {
				throw new \Exception("FAILED_TO_CREATE_USER_INVALID_PHONE_FORMAT");
				return false;
			}
			
			if(!empty($celular) && !Usuario::checkNumeroIntegrity($celular)) {
				throw new \Exception("FAILED_TO_CREATE_USER_INVALID_CELLPHONE_FORMAT");
				return false;
			}
			
			if(empty($clave)) {
				throw new \Exception("FAILED_TO_CREATE_USER_MISSING_PASSWORD");
				return false;
			}
			
			if(!Usuario::checkClaveIntegrity($clave)) {
				throw new \Exception("FAILED_TO_CREATE_USER_WEAK_PASSWORD");
				return false;
			}

			if(empty($fechaPago)) {
				throw new \Exception("FAILED_TO_CREATE_USER_MISSING_PAY_DATE");
				return false;
			}

			if(Usuario::checkFechaPagoIntegrity($fechaPago)) {
				throw new \Exception("FAILED_TO_CREATE_USER_INVALID_PAY_DATE");
				return false;
			}

			return true;
		}

		public static function registrar_director(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, int $fechaPago, bool $habilitado) : bool {
			if(!repo::check_valid_user_info($cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado)) return false;

			return controladorConexion::get()->query(
				"CALL REGISTER_DIRECTOR(?, ?, ?, ?, ?, ?, ?, ?);", "isssssii", 
				$cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado
			);
		}

		public static function registrar_administrador(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, int $fechaPago, bool $habilitado) : bool {
			if(!repo::check_valid_user_info($cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado)) return false;

			return controladorConexion::get()->query(
				"CALL REGISTER_ADMINISTRATOR(?, ?, ?, ?, ?, ?, ?, ?);", "isssssii", 
				$cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado
			);
		}

		public static function registrar_empleado(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, int $fechaPago, bool $habilitado) : bool {
			if(!repo::check_valid_user_info($cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado)) return false;
			
			return controladorConexion::get()->query(
				"CALL REGISTER_EMPLOYEE(?, ?, ?, ?, ?, ?, ?, ?);", "isssssii", 
				$cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, $habilitado
			);
		}

		public static function get_usuario_cedula(int $cedula) {
			if(empty($cedula)) {
				throw new \Exception("FAILED_TO_FIND_USER_MISSING_UID");
				return false;
			}

			return controladorConexion::get()->fetch_query(
				"CALL GET_USER_DATA(?);", "i", $cedula
			);
		}

		public static function modificar_usuario(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, int $fechaPago) : bool {
			if(!repo::check_valid_user_info($cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago, true)) 
				return false; // More detailed exceptions thrown on check

			return controladorConexion::get()->query(
				"CALL UPDATE_USER_DATA(?, ?, ?, ?, ?, ?, ?);", "isssssii", $cedula, $nombre, $correo, $telefono, $celular, $clave, $fechaPago
			);
		}

		public static function activar_usuario(int $cedula) : bool {
			if(empty($cedula)) {
				throw new \Exception("FAILED_TO_FIND_USER_MISSING_UID");
				return false;
			}

			return controladorConexion::get()->query(
				"CALL UPDATE_USER_ACCESS(?, true);", "i", $cedula
			);
		}

		public static function desactivar_usuario(int $cedula) : bool {
			if(empty($cedula)) {
				throw new \Exception("FAILED_TO_FIND_USER_MISSING_UID");
				return false;
			}
			
			return controladorConexion::get()->query(
				"CALL UPDATE_USER_ACCESS(?, false);", "i", $cedula
			);
		}

	/// @Consultas relacionadas a clientes
		private static function check_valid_client_info(string $alias, string $nombre) : bool {
			if(empty($alias)) {
				throw new \Exception("FAILED_TO_CREATE_CLIENT_EMPTY_ALIAS");
				return false;
			}

			if(!Cliente::checkAliasIntegrity($alias)) {
				throw new \Exception("FAILED_TO_CREATE_CLIENT_INVALID_ALIAS");
				return false;
			}

			if(empty($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_CLIENT_EMPTY_NAME");
				return false;
			}

			if(!Cliente::checkNombreIntegrity($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_CLIENT_INVALID_NAME");
				return false;
			}

			return true;
		}

		public static function registrar_cliente(string $alias, string $nombre, string $informacion) : bool {
			if(!repo::check_valid_client_info($alias, $nombre)) return false;
			
			return controladorConexion::get()->query(
				"CALL REGISTER_CLIENT(?, ?, ?);", "sss", 
				$alias, $nombre, $informacion
			);
		}

		public static function get_cliente_alias(string $alias) {
			if(empty($alias)) {
				return false;
				throw new \Exception("FAILED_TO_FIND_CLIENT_MISSING_ALIAS");
			}

			return controladorConexion::get()->fetch_query(
				"CALL GET_CLIENT_DATA(?);", "s", $alias
			);
		}

		public static function actualizar_nombre_cliente(string $alias, string $nombre) : bool {
			if(empty($alias)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_ALIAS");
				return false;
			}

			if(!Cliente::checkAliasIntegrity($alias)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_ALIAS");
				return false;
			}

			if(empty($nombre)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_NAME");
				return false;
			}

			if(!Cliente::checkNombreIntegrity($nombre)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_NAME");
				return false;
			}

			return controladorConexion::get()->query(
				"CALL UPDATE_CLIENT_NAME(?, ?);", "ss", $alias, $nombre
			);
		}
		
		public static function actualizar_info_cliente(string $alias, string $informacion) : bool {
			if(empty($alias)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_ALIAS");
				return false;
			}

			if(!Cliente::checkAliasIntegrity($alias)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_ALIAS");
				return false;
			}

			if(empty($informacion)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_INFO");
				return false;
			}

			return controladorConexion::get()->query(
				"CALL UPDATE_CLIENT_INFO(?, ?);", "ss", $alias, $informacion
			);
		}

		public static function modificar_cliente(string $alias, string $nombre, string $informacion) : bool {
			if(empty($alias)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_ALIAS");
				return false;
			}

			if(!Cliente::checkAliasIntegrity($alias)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_ALIAS");
				return false;
			}

			if(empty($nombre)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_NAME");
				return false;
			}

			if(!Cliente::checkNombreIntegrity($nombre)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_NAME");
				return false;
			}

			if(empty($informacion)) {
				throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_INFO");
				return false;
			}

			return controladorConexion::get()->query(
				"CALL UPDATE_CLIENT_DATA(?, ?, ?);", "sss", $alias, $nombre, $informacion
			);
		}

	/// @Consultas relacionadas a productos
		private static function check_valid_product_info(string $nombre, string $descripcion, int $stock, float $precio) : bool {
			if(empty($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_PRODUCT_EMPTY_NAME");
				return false;
			}
			
			if(!Producto::checkNombreIntegrity($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_PRODUCT_INVALID_NAME");
				return false;
			}
			
			if(!empty($descripcion)) {
				throw new \Exception("FAILED_TO_CREATE_PRODUCT_EMPTY_DESCRIPTION");
				return false;
			}

			if(!Producto::checkStockIntegrity($stock)) {
				throw new \Exception("FAILED_TO_CREATE_PRODUCT_INVALID_STOCK");
				return false;
			}

			if(!Producto::checkPrecioIntegrity($precio)) {
				throw new \Exception("FAILED_TO_CREATE_PRODUCT_INVALID_PRICE");
				return false;
			}

			return true;
		}

		public static function registrar_producto(string $nombre, string $descripcion, int $stock, float $precio) : bool {
			if(!repo::check_valid_product_info($nombre, $descripcion, $stock, $precio)) return false;

			return controladorConexion::get()->query(
				"CALL REGISTER_PRODUCT(?, ?, ?, ?);", "ssid", 
				$nombre, $descripcion, $stock, $precio
			);
		}

		public static function get_producto_id(int $uuid) {
			return controladorConexion::get()->fetch_query(
				"CALL GET_PRODUCT_DATA(?);", "i", 
				$uuid
			);
		}

		public static function modificar_producto(int $uuid, string $nombre, string $descripcion, int $stock) : bool {
			if(empty($nombre)) {
				throw new \Exception("FAILED_TO_UPDATE_PRODUCT_EMPTY_NAME");
				return false;
			}

			if(!Producto::checkNombreIntegrity($nombre)) {
				throw new \Exception("FAILED_TO_UPDATE_PRODUCT_INVALID_NAME");
				return false;
			}
			
			if(!empty($descripcion)) {
				throw new \Exception("FAILED_TO_UPDATE_PRODUCT_EMPTY_DESCRIPTION");
				return false;
			}

			if(!Producto::checkStockIntegrity($stock)) {
				throw new \Exception("FAILED_TO_UPDATE_PRODUCT_INVALID_STOCK");
				return false;
			}

			return controladorConexion::get()->query(
				"CALL UPDATE_PRODUCT(?, ?, ?, ?);", "issi", 
				$uuid, $nombre, $descripcion, $stock
			);
		}

		public static function actualizar_precio_producto(int $uuid, float $precio) : bool {
			if(!Producto::checkPrecioIntegrity($precio)) {
				throw new \Exception("FAILED_TO_CREATE_PRODUCT_INVALID_PRICE");
				return false;
			}

			return controladorConexion::get()->query(
				"CALL UPDATE_PRODUCT_PRICE(?, ?);", "id", 
				$uuid, $precio
			);
		}

	/// @Consultas relacionadas a contactos
		public static function check_valid_contact_info(string $tipo, int $creador, string $direccion, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : bool {
			if(empty($tipo)) {
				throw new \Exception("FAILED_TO_CREATE_CONTACT_MISSING_CONTACT_TYPE");
				return false;
			}

			if(empty($creador)) {
				throw new \Exception("FAILED_TO_CREATE_CONTACT_MISSING_CREATOR");
				return false;
			}

			if(empty($direccion)) {
				throw new \Exception("FAILED_TO_CREATE_CONTACT_MISSING_CONTACT_INFO");
				return false;
			}

			$valid_contact = match($tipo) {
				"Mail" 	   => 		Mail::checkMailIntegrity($direccion),
				"Telefono" => Telefono::checkNumeroIntegrity($direccion),
				"Celular"  =>  Celular::checkNumeroIntegrity($direccion),
				default => false
			};

			if(!$valid_contact) {
				throw new \Exception("FAILED_TO_CREATE_CONTACT_INVALID_CONTACT_INFO");
				return false;
			}

			if(!is_null($cliente))
				if(empty($cliente)) {
					throw new \Exception("FAILED_TO_CREATE_CONTACT_EMPTY_CLIENT_PROVIDED");
					return false;
				}

			if(!Contacto::checkClienteIntegrity($cliente)) {
				throw new \Exception("FAILED_TO_CREATE_CONTACT_INVALID_CLIENT");
				return false;
			}

			if(!is_null($aliases))
				if(empty($aliases)) {
					throw new \Exception("FAILED_TO_CREATE_CONTACT_EMPTY_ALIAS_LIST_PROVIDED");
					return false;
				}

			if(!Contacto::checkAliasesIntegrity($aliases)) {
				throw new \Exception("FAILED_TO_CREATE_CONTACT_ALAS_LIST_CONTAINS_INVALID_ALIAS");
				return false;
			}

			if(!is_null($tags))
				if(empty($tags)) {
					throw new \Exception("FAILED_TO_CREATE_CONTACT_EMPTY_TAG_LIST_PROVIDED");
					return false;
				}

			if(!Contacto::checkEtiquetasIntegrity($tags)) {
				throw new \Exception("FAILED_TO_CREATE_CONTACT_TAG_LIST_CONTAINS_INVALID_TAG");
				return false;
			}

			return true;
		}

		private static function registrar_datos_contacto(int $id, ?array $aliases = NULL, ?array $tags = NULL) : bool {
			if(!is_null($aliases)) foreach($aliases as $alias) {
			    $check = controladorConexion::get()->query(
					"CALL REGISTER_CONTACT_ALIAS(?, ?);", "is", 
					$id, $alias
				);

				if(!$check) {
					throw new \Exception("FAILED_TO_ASSIGN_ALIAS_TO_CONTACT");
					return false;
				}
			}

			if(!is_null($tags)) foreach($tags as $tag) {
			    $check = controladorConexion::get()->query(
					"CALL REGISTER_CONTACT_TAG(?, ?);", "is", 
					$id, $tag
				);

				if(!$check) {
					throw new \Exception("FAILED_TO_ASSIGN_TAG_TO_CONTACT");
					return false;
				}
			}

			return true;
		}

		public static function registrar_mail(int $creador, string $mail, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : ?int {
			if(!repo::check_valid_contact_info("Mail", $creador, $mail, $cliente, $aliases, $tags)) return NULL;

			$client_param = is_null($cliente) ? "NULL" : "?";
			$query = "CALL REGISTER_CONTACT_MAIL(?, ?, {$client_param});";
			$check = controladorConexion::get()->query(
				$query, is_null($cliente) ? "is" : "iss", 
				$creador, $mail, $cliente
			);

			$id = (int) controladorConexion::get()->fetch_query("SELECT ?;", "s", "LAST_INSERT_ID()")[0];

			if(repo::registrar_datos_contacto($id, $aliases, $tags)) 
				return $id;
		
			return NULL;
		}
		
		public static function registrar_telefono(int $creador, string $telefono, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : ?int {
			if(!repo::check_valid_contact_info("Telefono", $creador, $mail, $cliente, $aliases, $tags)) return NULL;

			$client_param = is_null($cliente) ? "NULL" : "?";
			$query = "CALL REGISTER_CONTACT_PHONE(?, ?, {$client_param});";
			$check = controladorConexion::get()->query(
				$query, is_null($cliente) ? "is" : "iss", 
				$creador, $mail, $cliente
			);

			$id = (int) controladorConexion::get()->fetch_query("SELECT ?;", "s", "LAST_INSERT_ID()")[0];

			if(repo::registrar_datos_contacto($id, $aliases, $tags)) 
				return $id;
		
			return NULL;
		}
		
		public static function registrar_celular(int $creador, string $celular, ?string $cliente = NULL, ?array $aliases = NULL, ?array $tags = NULL) : ?int {
			if(!repo::check_valid_contact_info("Celular", $creador, $mail, $cliente, $aliases, $tags)) return NULL;

			$client_param = is_null($cliente) ? "NULL" : "?";
			$query = "CALL REGISTER_CONTACT_CELLPHONE(?, ?, {$client_param});";
			$check = controladorConexion::get()->query(
				$query, is_null($cliente) ? "is" : "iss", 
				$creador, $mail, $cliente
			);

			$id = (int) controladorConexion::get()->fetch_query("SELECT ?;", "s", "LAST_INSERT_ID()")[0];

			if(repo::registrar_datos_contacto($id, $aliases, $tags)) 
				return $id;
		
			return NULL;
		}

		public static function get_contacto_id(int $id) {
			return controladorConexion::get()->fetch_query(
				"CALL GET_CONTACT_DATA(?);", "i", 
				$id
			);
		}

		public static function get_aliases_contacto_id(int $id) {
			return controladorConexion::get()->fetch_query(
				"CALL GET_CONTACT_ALIAS_DATA(?);", "i", 
				$id
			);
		}

		public static function get_etiquetas_contacto_id(int $id) {
			return controladorConexion::get()->fetch_query(
				"CALL GET_CONTACT_TAG_DATA(?);", "i", 
				$id
			);
		}

		public static function get_contactos_alias(string $alias) {
			return controladorConexion::get()->fetch_query(
				"CALL GET_CONTACTS_FROM_CLIENT(?);", "s", 
				$alias
			);
		}

		public static function asignar_cliente_contacto_id(int $id, string $alias) : bool {
			if(empty($alias)) {
				throw new \Exception("FAILED_TO_ASSIGN_CLIENT_TO_CONTACT_EMPTY_CONTACT");
				return false;
			}

			if(!Cliente::checkAliasIntegrity($alias)) {
				throw new \Exception("FAILED_TO_ASSIGN_CLIENT_TO_CONTACT_INVALID_CONTACT_ALIAS");
				return false;
			}

			return controladorConexion::get()->fetch_query(
				"CALL ASSIGN_CONTACT_CLIENT(?, ?);", "is", 
				$id, $alias
			);
		}

		public static function eliminar_contacto_id(int $id) {
			return controladorConexion::get()->query(
				"CALL DELETE_CONTACT(?);", "i", 
				$id
			);
		}

		public static function eliminar_alias_contacto_id(int $id, string $alias) {
			if(empty($alias)) {
				throw new \Exception("FAILED_TO_DELETE_CONTACT_ALIAS_EMPTY_ALIAS");
				return false;
			}

			if(!Contacto::checkAliasIntegrity($alias)) {
				throw new \Exception("FAILED_TO_DELETE_CONTACT_ALIAS_INVALID_CONTACT_ALIAS");
				return false;
			}

			return controladorConexion::get()->query(
				"CALL DELETE_ALIAS_FROM_CONTACT(?, ?);", "is", 
				$id, $alias
			);
		}
		
		public static function eliminar_etiqueta_contacto_id(int $id, string $etiqueta) {
			if(empty($etiqueta)) {
				throw new \Exception("FAILED_TO_DELETE_CONTACT_TAG_EMPTY_TAG");
				return false;
			}

			if(!Contacto::checkEtiquetaIntegrity($etiqueta)) {
				throw new \Exception("FAILED_TO_DELETE_CONTACT_TAG_INVALID_CONTACT_TAG");
				return false;
			}

			return controladorConexion::get()->fetch_query(
				"CALL DELETE_TAG_FROM_CONTACT(?, ?);", "is", 
				$id, $etiqueta
			);
		}

	/// @Consultas relacionadas a archivos
		public static function check_valid_file_info(string $tipo, string $path, int $creador, string $nombre, int $fechaCreacion, mixed ...$args) : bool {
			if(empty($tipo)) {
				throw new \Exception("FAILED_TO_CREATE_FILE_MISSING_TYPE");
				return false;
			}

			if(empty($path)) {
				throw new \Exception("FAILED_TO_CREATE_FILE_MISSING_FILEPATH");
				return false;
			}

			if(!Archivo::checkFilePathIntegrity($path)) {
				throw new \Exception("FAILED_TO_CREATE_FILE_INVALID_FILEPATH");
				return false;
			}

			if(empty($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_FILE_EMPTY_FILENAME");
				return false;
			}

			if(!Archivo::checkNombreIntegrity($nombre)) {
				throw new \Exception("FAILED_TO_CREATE_FILE_INVALID_FILENAME");
				return false;
			}

			$check = false;

			switch($tipo) {
				case 'Compra':
					$check = func_num_args() !== 6;
					break;
				case 'Venta':
					$check = func_num_args() !== 5;
					break;
				case 'Documento':
					$check = func_num_args() !== 7;
					break;
				case 'Sueldo':
					$check = func_num_args() !== 6;
					break;
				default:
					throw new \Exception("FAILED_TO_CREATE_FILE_INVALID_TYPE");
					return false;
			}

			if($check) {
				throw new \Exception("FAILED_TO_CREATE_FILE_INVALID_NUMBER_OF_ARGUMENTS");
				return false;
			}

			return true;
		}

		public static function registrar_archivo_compra(string $path, int $creador, string $nombre, int $fechaCreacion, int $comprador) : bool {
			if(!repo::check_valid_file_info("Compra", $path, $creador, $nombre, $fechaCreacion, $comprador)) return false;

			return controladorConexion::get()->query(
				"CALL REGISTER_PURCHASE(?, ?, ?, ?, ?);", "sisii", 
				$path, $creador, $nombre, $fechaCreacion, $comprador
			);
		}
		
		public static function registrar_archivo_venta(string $path, int $creador, string $nombre, int $fechaCreacion) : bool {
			if(!repo::check_valid_file_info("Venta", $path, $creador, $nombre, $fechaCreacion)) return false;

			return controladorConexion::get()->query(
				"CALL REGISTER_SALE(?, ?, ?, ?);", "sisi", 
				$path, $creador, $nombre, $fechaCreacion
			);
		}
		
		public static function registrar_documento(string $path, int $creador, string $nombre, int $fechaCreacion, string $descripcion, bool $visible) : bool {
			if(!repo::check_valid_file_info("Documento", $path, $creador, $nombre, $fechaCreacion, $descripcion, $visible)) return false;

			return controladorConexion::get()->query(
				"CALL REGISTER_DOCUMENT(?, ?, ?, ?, ?, ?);", "sisisi", 
				$path, $creador, $nombre, $fechaCreacion, $descripcion, $visible
			);
		}
		
		public static function registrar_sueldo(string $path, int $creador, string $nombre, int $fechaCreacion, int $recipiente) : bool {
			if(!repo::check_valid_file_info("Sueldo", $path, $creador, $nombre, $fechaCreacion, $recipiente)) return false;

			return controladorConexion::get()->query(
				"CALL REGISTER_PAYMENT(?, ?, ?, ?, ?);", "sisii", 
				$path, $creador, $nombre, $fechaCreacion, $recipiente
			);
		}

		public static function get_file_from_path(string $path) {
			if(!Archivo::checkFilePathIntegrity($path)) {
				throw new \Exception("FAILED_TO_GET_FILE_INVALID_PATH");
				return false;
			}

			return controladorConexion::get()->fetch_query(
				"CALL GET_FILE_DATA(?);", "s", 
				$path
			);			
		}
}

?>