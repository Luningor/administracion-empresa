<?php

namespace LBBAS\app\core;

spl_autoload_register(
	function ($clase) {
		$ruta = __DIR__."/../../".str_replace("App\\", "", $clase).".php";

		if(file_exists($ruta)) require_once $ruta;
	}
)

?>