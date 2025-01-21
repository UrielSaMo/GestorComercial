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
}
?>