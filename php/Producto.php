<?php
require_once './ConexionBD.php';

class Producto {
    private $pdo;

    public function __construct() {
        $connection = new ConexionDB();
        $this->pdo = $connection->connect();
    }

    public function actualizarProducto($id, $nombre, $precio, $categoria, $stock, $estado) {
        try {
            $sql = "UPDATE productos SET Nombre = :nombre, Precio = :precio, Categoria = :categoria, Stock = :stock, Estado = :estado WHERE IDProducto = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return ['success' => true, 'message' => 'El producto se actualizó correctamente.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al actualizar el producto: ' . $e->getMessage()];
        }
    }

    public function obtenerStock($idProducto) {
        try {
            $sql = "SELECT Stock FROM productos WHERE IDProducto = :idProducto FOR UPDATE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $stmt->execute();

            $stock = $stmt->fetchColumn();
            if ($stock === false) {
                throw new Exception("Producto con ID $idProducto no encontrado.");
            }

            return $stock;
        } catch (Exception $e) {
            throw new Exception('Error al obtener el stock: ' . $e->getMessage());
        }
    }

    public function actualizarStock($idProducto, $cantidad) {
        try {
            $sql = "UPDATE productos SET Stock = Stock - :cantidad WHERE IDProducto = :idProducto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true, 'message' => 'El stock se actualizó correctamente.'];
        } catch (Exception $e) {
            throw new Exception('Error al actualizar el stock: ' . $e->getMessage());
        }
    }

    public function agregarProductos($nombresProducto, $precios, $categorias, $stocks, $estados, $idTienda) {
        $detalleProductos = [];

        try {
            $this->pdo->beginTransaction();

            $checkDuplicateSql = "SELECT Nombre, IDTienda FROM productos WHERE Nombre = ? AND IDTienda = ?";
            $checkDuplicateStmt = $this->pdo->prepare($checkDuplicateSql);

            $insertSql = "INSERT INTO `productos` (`Nombre`, `Categoria`, `Precio`, `Stock`, `Estado`, `IDTienda`) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $this->pdo->prepare($insertSql);

            $cantidadProductos = count($nombresProducto);

            for ($i = 0; $i < $cantidadProductos; $i++) {
                $nombre = $nombresProducto[$i];
                $precio = $precios[$i];
                $categoria = $categorias[$i];
                $stock = $stocks[$i];
                $estado = $estados[$i];

                $checkDuplicateStmt->execute([$nombre, $idTienda]);
                if ($checkDuplicateStmt->rowCount() > 0) {
                    $detalleProductos[] = ['estatus' => false, 'nombre' => $nombre, 'razon' => 'Producto duplicado en esta tienda'];
                    continue;
                }

                if ($precio < 0 || $stock < 0) {
                    $detalleProductos[] = ['estatus' => false, 'nombre' => $nombre, 'razon' => 'Precio o stock negativo'];
                    continue;
                }

                $insertStmt->execute([$nombre, $categoria, $precio, $stock, $estado, $idTienda]);
                $detalleProductos[] = ['estatus' => true, 'nombre' => $nombre, 'razon' => 'Producto registrado exitosamente'];
            }

            $this->pdo->commit();

            return $detalleProductos;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception('Error al agregar productos: ' . $e->getMessage());
        }
    }

}
?>