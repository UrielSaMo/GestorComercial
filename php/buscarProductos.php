<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

require_once './ConexionBD.php';
header('Content-Type: application/json');

try {
    $textoBusqueda = trim($_GET['q'] ?? '');

    $conexionDB = new ConexionDB();
    $conn = $conexionDB->connect();

    // Modificar la consulta SQL para incluir IDProducto
    $sql = "SELECT IDProducto, Nombre, Precio, Stock, Estado FROM productos"; // Se añade IDProducto
    if (!empty($textoBusqueda)) {
        $sql .= " WHERE Nombre LIKE :busqueda";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($textoBusqueda)) {
        $textoBusqueda = "%$textoBusqueda%";
        $stmt->bindParam(':busqueda', $textoBusqueda, PDO::PARAM_STR);
    }

    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Responder con los datos incluyendo el IDProducto
    echo json_encode(['success' => true, 'productos' => $productos]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
