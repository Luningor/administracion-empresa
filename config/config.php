<?php

namespace LBBAS\config;

class config {
	/// @Getters
	public static function get() {
		global $_CONFIG;
		$_CONFIG = [];
		$_CONFIG["DB_CONN_SRV"] = "localhost";
		$_CONFIG["DB_CONN_DBN"] = "empresa";
		$_CONFIG["DB_CONN_USR"] = "empresa_admin";
		$_CONFIG["DB_CONN_PWD"] = "!DefaultAdminPassword1234";
	}

	/// @Setters

	/// @Methods
	
}

?>