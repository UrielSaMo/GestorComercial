<?php
session_start();
require_once './ConexionBD.php';
header('Content-Type: application/json');

$connection = new ConexionDB();
$pdo = $connection->connect();

try {
    // Consultar los productos de la tienda asociada al usuario
    $sql = "SELECT p.IDProducto, p.Nombre, p.Precio, p.Categoria, p.Stock, p.Estado
            FROM productos p
            INNER JOIN usuario u ON p.IDTienda = u.IDTienda
            WHERE u.Correo = :correo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':correo', $_SESSION['correo'], PDO::PARAM_STR);
    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($productos) > 0) {
        echo json_encode(['success' => true, 'productos' => $productos]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron productos.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener productos: ' . $e->getMessage()]);
}
?>
