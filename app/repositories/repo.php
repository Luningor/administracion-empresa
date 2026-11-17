<?php

namespace LBBAS\app\repositories;
use LBBAS\app\controllers\controladorConexion as controladorConexion;

class repo {
	// Consultas relacionadas a usuarios
	private static function check_valid_user_info(int $cedula, string $nombre, string $correo, string $telefono, string $celular, string $clave, int $fechaPago) : bool {
		if(empty($cedula))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_UID");
		
		if(empty($nombre))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_NAME");
		
		if(empty($correo) && empty($telefono) && empty($celular)) 
			throw new \Exception("FAILED_TO_CREATE_USER_NO_CONTACT_INFO");
		
		if(!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) 
			throw new \Exception("FAILED_TO_CREATE_USER_INVALID_MAIL_FORMAT");
		
		if(empty($clave))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_PASSWORD");
		
		if(empty($fechaPago))
			throw new \Exception("FAILED_TO_CREATE_USER_MISSING_PAY_DATE");

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
}

?>