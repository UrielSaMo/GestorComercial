<?php
require_once './Producto.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombresProducto = $_POST['nombreProducto'] ?? [];
    $precios = $_POST['precio'] ?? [];
    $categorias = $_POST['categoria'] ?? [];
    $stocks = $_POST['stock'] ?? [];
    $estados = $_POST['estado'] ?? [];
    $idTienda = $_SESSION['tienda_id'] ?? 1;
    

    if (!empty($nombresProducto) && !empty($precios) && !empty($categorias) && !empty($stocks) && !empty($estados)) {
        $producto = new Producto();
        try {
            $detalleProductos = $producto->agregarProductos(
                $nombresProducto, 
                $precios, 
                $categorias, 
                $stocks, 
                $estados, 
                $idTienda
            );

            $_SESSION['detalleProductos'] = $detalleProductos;

            echo json_encode([
                'success' => true,
                'message' => 'Productos procesados.',
                'redirectUrl' => '../GestorComercial/reporteProductos.php'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
    }
}
