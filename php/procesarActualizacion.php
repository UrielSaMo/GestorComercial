<?php
require_once './ConexionBD.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    $estado = $_POST['estado'];

    $connection = new ConexionDB();
    $pdo = $connection->connect();

    try {
        $sql = "UPDATE productos SET Nombre = :nombre, Precio = :precio, Categoria = :categoria, Stock = :stock, Estado = :estado WHERE IDProducto = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'El producto se actualizó correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto: ' . $e->getMessage()]);
    }
}
