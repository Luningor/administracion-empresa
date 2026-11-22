<?php

namespace LBBAS\app\repositories;
use LBBAS\app\controllers\controladorConexion as controladorConexion;
use LBBAS\app\models\Usuario as Usuario;
use LBBAS\app\models\Cliente as Cliente;

class repo {
	/// Consultas relacionadas a usuarios
	private static function check_valid_user_info(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, int $fechaPago) : bool {
		if(empty($cedula))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_UID");
		
		if(empty($nombre))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_NAME");
		
		if(!Usuario::checkNombreIntegrity($nombre))
			throw new \Exception("FAILED_TO_CREATE_USER_INVALID_NAME");

		if(empty($correo) && empty($telefono) && empty($celular)) 
			throw new \Exception("FAILED_TO_CREATE_USER_NO_CONTACT_INFO");
		
		if(!empty($correo) && !Usuario::checkCorreoIntegrity($correo)) 
			throw new \Exception("FAILED_TO_CREATE_USER_INVALID_MAIL_FORMAT");
		
		if(!empty($telefono) && !Usuario::checkNumeroIntegrity($telefono)) 
			throw new \Exception("FAILED_TO_CREATE_USER_INVALID_PHONE_FORMAT");
		
		if(!empty($celular) && !Usuario::checkNumeroIntegrity($celular)) 
			throw new \Exception("FAILED_TO_CREATE_USER_INVALID_CELLPHONE_FORMAT");
		
		if(empty($clave))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_PASSWORD");
		
		if(!Usuario::checkClaveIntegrity($clave))
			throw new \Exception("FAILED_TO_CREATE_USER_WEAK_PASSWORD");

		if(empty($fechaPago))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_PAY_DATE");

		if(Usuario::checkFechaPagoIntegrity($fechaPago))
			throw new \Exception("FAILED_TO_CREATE_USER_INVALID_PAY_DATE");

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
		if(empty($cedula)) throw new \Exception("FAILED_TO_FIND_USER_MISSING_UID");

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
		if(empty($cedula)) throw new \Exception("FAILED_TO_FIND_USER_MISSING_UID");

		return controladorConexion::get()->query(
			"CALL UPDATE_USER_ACCESS(?, true);", "i", $cedula
		);
	}

	public static function desactivar_usuario(int $cedula) : bool {
		if(empty($cedula)) throw new \Exception("FAILED_TO_FIND_USER_MISSING_UID");
		
		return controladorConexion::get()->query(
			"CALL UPDATE_USER_ACCESS(?, false);", "i", $cedula
		);
	}

	/// Consultas relacionadas a clientes
	private static function check_valid_client_info(string $alias, string $nombre) : bool {
		if(empty($alias)) throw new \Exception("FAILED_TO_CREATE_CLIENT_EMPTY_ALIAS");
		if(!Cliente::checkAliasIntegrity($alias)) throw new \Exception("FAILED_TO_CREATE_CLIENT_INVALID_ALIAS");

		if(empty($nombre)) throw new \Exception("FAILED_TO_CREATE_CLIENT_EMPTY_NAME");
		if(!Cliente::checkNombreIntegrity($nombre)) throw new \Exception("FAILED_TO_CREATE_CLIENT_INVALID_NAME");

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
		if(empty($alias)) throw new \Exception("FAILED_TO_FIND_CLIENT_MISSING_ALIAS");

		return controladorConexion::get()->fetch_query(
			"CALL GET_CLIENT_DATA(?);", "s", $alias
		);
	}

	public static function actualizar_nombre_cliente(string $alias, string $nombre) : bool {
		if(empty($alias)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_ALIAS");
		if(!Cliente::checkAliasIntegrity($alias)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_ALIAS");

		if(empty($nombre)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_NAME");
		if(!Cliente::checkNombreIntegrity($nombre)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_NAME");

		return controladorConexion::get()->query(
			"CALL UPDATE_CLIENT_NAME(?, ?);", "ss", $alias, $nombre
		);
	}
	
	public static function actualizar_info_cliente(string $alias, string $informacion) : bool {
		if(empty($alias)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_ALIAS");
		if(!Cliente::checkAliasIntegrity($alias)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_ALIAS");

		if(empty($informacion)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_INFO");

		return controladorConexion::get()->query(
			"CALL UPDATE_CLIENT_INFO(?, ?);", "ss", $alias, $informacion
		);
	}

	public static function modificar_cliente(string $alias, string $nombre, string $informacion) : bool {
		if(empty($alias)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_ALIAS");
		if(!Cliente::checkAliasIntegrity($alias)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_ALIAS");

		if(empty($nombre)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_NAME");
		if(!Cliente::checkNombreIntegrity($nombre)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_INVALID_NAME");

		if(empty($informacion)) throw new \Exception("FAILED_TO_UPDATE_CLIENT_EMPTY_INFO");

		return controladorConexion::get()->query(
			"CALL UPDATE_CLIENT_DATA(?, ?, ?);", "sss", $alias, $nombre, $informacion
		);
	}
}

?>