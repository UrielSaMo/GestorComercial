<?php
require_once './Producto.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    $estado = $_POST['estado'];

    $producto = new Producto();
    $resultado = $producto->actualizarProducto($id, $nombre, $precio, $categoria, $stock, $estado);

    echo json_encode($resultado);
}
?>