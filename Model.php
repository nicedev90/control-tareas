<?php 
	require 'Database.php';

	class Model extends Database {
		protected $db;

		public function __construct() {
			$this->db = new Database;
		}

		//**** Inicio funciones de SESION DE USUARIO
		// Obtener informacion de Perfil de usuario
		public function getDatosUsuario($id) {
			$query = "SELECT *, r.rol as rol_nombre FROM usuarios u 
			INNER JOIN rol r on u.rol = r.idrol 
			WHERE idusuarios = '$id' ";
			
			$this->db->prepare($query);
			$perfil = $this->db->fetch();

			if ($perfil) {
				return $perfil;
			} else {
				return false;
			}
		}


		// obtener usuario de base de datos para el Login
		public function readRooms() {
			$query = "SELECT * FROM rooms WHERE room_status =1 ";
			$this->db->prepare($query);
			return $this->db->fetchAll() ?? false;
		}

		public function readAsignaciones() {
			$query = "SELECT * FROM asignaciones WHERE asignacion_status =1 ";
			$this->db->prepare($query);
			return $this->db->fetchAll() ?? false;
		}

		public function readDeskOcupados() {
			$query = "SELECT * FROM desks WHERE desk_status =0 ";
			$this->db->prepare($query);
			return $this->db->fetchAll() ?? false;
		}

		public function readTiempoDesk($desk_num) {
			$query = "SELECT desk_time, desk_paused FROM desks WHERE desk_num = $desk_num AND desk_status =0 ";
			$this->db->prepare($query);
			return $this->db->fetch() ?? false;
		}

		public function readDeskByNum($desk_num) {
			$query = "SELECT * FROM desks WHERE desk_num = '$desk_num' AND desk_status =0 ";
			$this->db->prepare($query);
			return $this->db->fetch() ?? false;
		}

		public function saveTareasCompletadas($puesto,$asignacion,$cantidad) {
			$query = "INSERT INTO tareas_completadas (puesto,asignacion,cantidad) VALUES ('$puesto','$asignacion','$cantidad')";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}

		public function createDesk($room_id,$desk_num,$desk_time) {
			$query = "INSERT INTO desks (room_id,desk_num,desk_time,desk_status) VALUES ('$room_id','$desk_num','$desk_time', 0)";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}


		public function updateDesk($room_id,$desk_num,$desk_time) {
			$query = "UPDATE desks SET desk_time = '$desk_time' WHERE desk_num = '$desk_num'";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}

		public function updatePauseDesk($desk_num,$desk_paused) {
			$query = "UPDATE desks SET desk_paused = '$desk_paused' WHERE desk_num = '$desk_num'";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}


		public function deleteDesk($desk_num) {
			$query = "DELETE FROM desks WHERE desk_num = '$desk_num'";
			$this->db->prepare($query);
			return $this->db->execute() ?? false;
		}



		// traer lista de todos los usuarios/clientes
		public function readTodosUsuarios() {
			$query = "SELECT * FROM usuarios ";
			$this->db->prepare($query);
			$all = $this->db->fetchAll();

			if ($all) {
				return $all;
			} else {
				return false;
			}
		}

		// **** fin funciones para tabla usuarios

		// **** inicio funciones para tabla Proveedores
		public function createProveedor($cuit, $razon_social_proveedor, $direccion_proveedor, $telefono_proveedor, $mail_proveedor) {
			$query = "INSERT INTO proveedores (cuit, razon_social_proveedor, direccion_proveedor, telefono_proveedor, mail_proveedor) 
			VALUES ('$cuit', '$razon_social_proveedor', '$direccion_proveedor', '$telefono_proveedor', '$mail_proveedor')";
			$this->db->prepare($query);
			$created = $this->db->execute();

			if ($created) {
				return true;
			} else {
				return false;
			}
		}

		// obtener lista de proveedores 
		public function readProveedores() {
			$query = "SELECT * FROM proveedores";
			$this->db->prepare($query);
			$row = $this->db->fetchAll();

			if ($row) {
				return $row;
			} else {
				return false;
			}
		}


		// Actualizar informacion de tabla proveedores
		public function updateProveedor($cuit, $direccion_proveedor, $telefono_proveedor, $mail_proveedor) {
			$query = "UPDATE proveedores 
			SET direccion_proveedor = '$direccion_proveedor',
			 telefono_proveedor = '$telefono_proveedor',
			 mail_proveedor = '$mail_proveedor'
			 
			WHERE cuit = '$cuit'";

			$this->db->prepare($query);
			$updated = $this->db->execute();

			if ($updated) {
				return true;
			} else {
				return false;
			}
		}

		// eliminar producto de la tabla Proveedores
		public function deleteProveedor($cuit) {
			$query = "DELETE FROM proveedores WHERE cuit = '$cuit'";
			$this->db->prepare($query);
			$deleted = $this->db->execute();

			if ($deleted) {
				return true;
			} else {
				return false;
			}
		}
		// *** fin funciones para tabla Proveedores

		// *** inicio funciones para tabla productos

		public function createProducto($nombre_producto, $descripcion_producto, $precio_costo_producto, $precio_venta_producto, $stock_producto) {
			$query = "INSERT INTO productos (nombre_producto, descripcion_producto, precio_costo_producto, precio_venta_producto, stock_producto) 
			VALUES ('$nombre_producto', '$descripcion_producto', '$precio_costo_producto', '$precio_venta_producto', '$stock_producto')";
			$this->db->prepare($query);
			$created = $this->db->execute();

			if ($created) {
				return true;
			} else {
				return false;
			}
		}


		// obtener lista de productos que tienen stock 
		public function readProductos() {
			$query = "SELECT * FROM productos WHERE stock_producto > 0 ORDER BY id_producto DESC";
			$this->db->prepare($query);
			$productos = $this->db->fetchAll();

			if ($productos) {
				return $productos;
			} else {
				return false;
			}
		}

		// Actualizar informacion de tabla producto
		public function updateProducto($id_producto, $nombre_producto, $descripcion_producto, $precio_costo_producto, $precio_venta_producto, $stock_producto) {
			$query = "UPDATE productos 
			SET nombre_producto = '$nombre_producto',
			 descripcion_producto = '$descripcion_producto',
			 precio_costo_producto = '$precio_costo_producto',
			 precio_venta_producto = '$precio_venta_producto',
			 stock_producto = '$stock_producto'
			WHERE id_producto = '$id_producto'";

			$this->db->prepare($query);
			$updated = $this->db->execute();

			if ($updated) {
				return true;
			} else {
				return false;
			}
		}

		// eliminar producto de la tabla Productos
		public function deleteProducto($id_producto) {
			$query = "DELETE FROM productos WHERE id_producto = '$id_producto'";
			$this->db->prepare($query);
			$deleted = $this->db->execute();

			if ($deleted) {
				return true;
			} else {
				return false;
			}
		}
		// *** fin funciones para tabla productos




		// **** inicio funciones para tabla Ventas

		// registrar venta usando rollback si  cantidad supera stock del producto
		public function createVenta($id_usuario, $fecha_venta, $item) {
			try {
				$this->db->beginTransaction();   

				$query = "INSERT INTO ventas (id_usuario, fecha_venta) VALUES ('$id_usuario', '$fecha_venta')";
				$this->db->prepare($query);
				$this->db->execute();

				$nro_venta = $this->getNumVenta();
				$errores = [];

					foreach ($item as $row) {
						$stock = $this->getStockProducto($row['id_producto']);

						if($stock >= $row['cantidad']) {
							$query = "INSERT INTO venta_items (nro_venta, id_producto, cantidad) VALUES ('$nro_venta', '$row[id_producto]', '$row[cantidad]')";
							$this->db->prepare($query);
							$this->db->execute();

						} else {
							array_push($errores, 'error no hay stock suficiente.');
						}
					}
			
				if(count($errores) == 0) {
					$this->db->commit();
					return true;
				} else {
					return false;
				}

			} catch (Exception $e) {
				$this->db->rollBack();
    		return $e;
			}

		}

		public function readVentas() {
			$query = "SELECT *, v.nro_venta AS id_venta 
			FROM ventas v 
			INNER JOIN usuarios u on u.idusuarios = v.id_usuario
			ORDER BY v.nro_venta DESC";
			
			$this->db->prepare($query);
			$ventas = $this->db->fetchAll();

			if ($ventas) {
				return $ventas;
			} else {
				return false;
			}
		}


		public function deleteVenta($nro_venta) {
			$query = "DELETE FROM ventas WHERE nro_venta = '$nro_venta'";
			$this->db->prepare($query);
			$deleted = $this->db->execute();

			if ($deleted) {
				return true;
			} else {
				return false;
			}

		}



		public function readVentaItems($nro_venta) {
			$query = "SELECT * FROM venta_items it INNER JOIN productos p on p.id_producto = it.id_producto WHERE it.nro_venta = '$nro_venta'";
			
			$this->db->prepare($query);
			$items = $this->db->fetchAll();

			if ($items) {
				return $items;
			} else {
				return false;
			}	
		}

		public function updateVentaItem($id_item, $cantidad) {
			$query = "UPDATE venta_items 
			SET cantidad = '$cantidad'
			WHERE id = '$id_item'";

			$this->db->prepare($query);
			$updated = $this->db->execute();

			if ($updated) {
				return true;
			} else {
				return false;
			}
		}

		public function deleteVentaItem($id_item) {
			$query = "DELETE FROM venta_items WHERE id = '$id_item'";
			$this->db->prepare($query);
			$deleted = $this->db->execute();

			if ($deleted) {
				return true;
			} else {
				return false;
			}

		}


		// obtener ultimo nro_venta de tabla Ventas
		public function getNumVenta() {
			$query = "SELECT MAX(nro_venta) AS numVenta FROM ventas";
			$this->db->prepare($query);
			$num = $this->db->fetch();
			return $num->numVenta;
		}

		// obtener stock de producto en tabla Productos
		public function getStockProducto($id_producto) {
			$query = "SELECT stock_producto FROM productos WHERE id_producto = '$id_producto'";
			$this->db->prepare($query);
			$stock = $this->db->fetch();
			return $stock->stock_producto;
		}

		// **** fin funciones para tabla Ventas



	}
