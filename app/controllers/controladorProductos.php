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
	public static function get() : controladorProductos {
		controladorProductos::$instance = controladorProductos::$instance ?? new controladorProductos();
		return controladorProductos::$instance;
	}

	public function getProductoId(int $id) : ?Producto {
		if(isset($this->productos[$id])) return $this->productos[$id];
		else {
			// Get from database
			$queryResult = repo::get_producto_id($id);

			// No client with that alias
			if(is_null($queryResult)) {
				throw new \Exception("FAILED_TO_GET_PRODUCT_NO_PRODUCT_WITH_ID");
				return NULL;
			}

			if(isset($queryResult[0]["precioAnterior"])) {
				// Create object with oldest price (since the result is in ascending order)
				$this->productos[$id] = new Producto(
					$id, $queryResult[0]["nombre"], $queryResult[0]["descripcion"], $queryResult[0]["stock"], $queryResult[0]["precioAnterior"]
				);

				// Update it chronologically
				if(isset($queryResult[1])) // Check that there's at least one more record
					for($i = 1; $i < count($queryResult); $i++)
						$this->productos[$id]->setPrecio($queryResult[$i]["precioAnterior"]);
				
				// End with current price
				$this->productos[$id]->setPrecio($queryResult[0]["precio"]);
			}
			else
				$this->productos[$id] = new Producto(
					$id, $queryResult[0]["nombre"], $queryResult[0]["descripcion"], $queryResult[0]["stock"], $queryResult[0]["precio"]
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
		if(repo::actualizar_precio_producto($uuid, $precio)) {
			$this->getProductoId($uuid)->setPrecio();
			return true;
		}

		return false;
	}

	public function buscarProducto(string $text) : array {

	}
}

?>