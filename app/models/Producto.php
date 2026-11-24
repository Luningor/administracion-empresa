<?php

namespace LBBAS\app\models;

class Producto {
	private int $uuid;
	private string $nombre;
	private string $descripcion;
	private int    $stock;
	private float  $precio;
	private array  $historialPrecio;

	/// @Constructor
	public function __construct(string $UUID, string $NOMBRE, string $DESC, int $STOCK, float $PRECIO) {
		$this->uuid 	   = $UUID;
		$this->nombre	   = $NOMBRE;
		$this->descripcion = $DESC;
		$this->stock	   = $STOCK;
		$this->precio 	   = $PRECIO;
		$this->historialPrecio = [];
	}

	/// @Destructor
	public function __destruct() {}

	/// @Getters

	/// @Setters
	public function setNombre(string $nombre) : bool {
		if(!Producto::checkNombreIntegrity($nombre)) return false;

		$this->nombre = $nombre;
		return true;
	}

	public function setDescripcion(string $desc) : bool {
		if(!empty($desc)) return false;
		
		$this->descripcion = $desc;
		return true;
	}
	
	public function setStock(int $stock) : bool {
		if(!Producto::checkStockIntegrity($stock)) return false;
		
		$this->stock = $stock;
		return true;
	}
	
	public function setPrecio(float $precio) : bool {
		if(!Producto::checkPrecioIntegrity($precio)) return false;
		
		$this->historialPrecio[] = [$this->precio, time()];
		$this->precio = $precio;
		return true;
	}

	/// @Methods
	public static function checkNombreIntegrity(string $nombre) : bool {
		// Only letters with points, dashes, hashtags and spaces inbetween, at least 4 characters long
		return preg_match("/^(?=^[a-zA-z].*[a-zA-Z]*$)(?=.*[ \.\-\#]*)(?!^[ \.\-\#].*)(?!.*[ \.\-\#]$).{4,}$/", $nombre);
	}

	public static function checkStockIntegrity(int $stock) : bool {
		return $stock >= 0;
	}

	public static function checkPrecioIntegrity(float $precio) : bool {
		return $precio >= 0.0;
	}
}

?>