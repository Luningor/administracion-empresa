<?php

namespace LBBAS\app\controllers;
require_once __DIR__ . "/../core/autoload.php";
use LBBAS\app\models\Producto as Producto;
use LBBAS\app\repositories\repo as repo;

class controladorProductos {
	static private controladorProductos $instance;
	private array						$productos;

	/// @Constructor
	public function __construct() {}

	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() {
		controladorProductos::$instance = controladorProductos::$instance ?? new controladorProductos();
		return controladorProductos::$instance;
	}

	public function getProductoId(int $id) : Producto {
		if(isset($this->productos[$id])) return $this->productos[$id];
		else {
			// Get from database
			$queryResult = repo::get_producto_id($id);

			// No client with that alias
			if(is_null($queryResult))
				throw new \Exception("FAILED_TO_GET_PRODUCT_NO_PRODUCT_WITH_ID");

			$this->productos[$id] = new Producto(
				$id, $queryResult["nombre"], $queryResult["descripcion"], $queryResult["stock"], $queryResult["precio"]
			);

			return $this->productos[$id];
		}
	}

	/// @Setters

	/// @Methods
	public function crearProducto(string $nombre, string $descripcion, int $stock, float $precio) : bool {
		$id = repo::registrar_producto($nombre, $descripcion, $stock, $precio);
		if(!is_null($id)) {
			$this->productos[$id] = new Producto($id, $nombre, $descripcion, $stock, $precio);
			return true;
		}

		return false;
	}

	public function modificarProducto(int $uuid, string $nombre, string $descripcion, int $stock) : bool {
		if(repo::modificar_producto($uuid, $nombre, $descripcion, $stock)) {
			$producto = $this->getProductoId($uuid);

			$producto->setNombre($nombre);
			$producto->setDescripcion($descripcion);
			$producto->setStock($stock);
			return true;
		}

		return false;
	}

	public function actualizarPrecioProducto(int $uuid, float $precio) : bool {

	}

	public function buscarProducto(string $text) : array {

	}
}

?>