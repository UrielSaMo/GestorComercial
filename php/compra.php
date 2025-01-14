<?php
session_start();
require_once './ConexionBD.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productos'], $_POST['idUsuario'], $_POST['idTienda'], $_POST['total'])) {
    $productosJson = $_POST['productos'];
    $productos = json_decode($productosJson, true);
    $idUsuario = (int)$_POST['idUsuario'];
    $idTienda = (int)$_POST['idTienda'];
    $total = (float)$_POST['total'];

    if (empty($productos) || $total <= 0) {
        echo json_encode(['success' => false, 'message' => 'El carrito está vacío o los datos son inválidos.']);
        exit;
    }

    foreach ($productos as $producto) {
        if ((int)$producto['Cantidad'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'La cantidad de productos debe ser mayor a 0.']);
            exit;
        }
    }

    try {
        $connection = new ConexionDB();
        $pdo = $connection->connect();

        // Iniciar la transacción
        $pdo->beginTransaction();

        foreach ($productos as $producto) {
            $idProducto = (int)$producto['IDProducto'];
            $cantidad = (int)$producto['Cantidad'];

            $stockQuery = "SELECT Stock FROM productos WHERE IDProducto = :idProducto FOR UPDATE";
            $stockStmt = $pdo->prepare($stockQuery);
            $stockStmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $stockStmt->execute();

            $stock = $stockStmt->fetchColumn();
            if ($stock === false) {
                throw new Exception("Producto con ID $idProducto no encontrado.");
            }

            if ($cantidad > $stock) {
                throw new Exception("No hay suficiente stock para el producto con ID $idProducto. Disponible: $stock, Solicitado: $cantidad.");
            }
        }

        // Insertar la nueva venta y procesar el detalle (igual que antes)
        $ventaSql = "INSERT INTO ventas (IDUsuario, IDTienda, Total, FechaVenta) VALUES (:idUsuario, :idTienda, :total, NOW())";
        $ventaStmt = $pdo->prepare($ventaSql);
        $ventaStmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $ventaStmt->bindParam(':idTienda', $idTienda, PDO::PARAM_INT);
        $ventaStmt->bindParam(':total', $total, PDO::PARAM_STR);
        $ventaStmt->execute();

        $idVenta = $pdo->lastInsertId();

        $detalleSql = "INSERT INTO detalleventa (IDVenta, IDProducto, Cantidad, Subtotal) VALUES (:idVenta, :idProducto, :cantidad, :subtotal)";
        $detalleStmt = $pdo->prepare($detalleSql);
        $updateStockSql = "UPDATE productos SET Stock = Stock - :cantidad WHERE IDProducto = :idProducto";
        $updateStockStmt = $pdo->prepare($updateStockSql);

        foreach ($productos as $producto) {
            $idProducto = (int)$producto['IDProducto'];
            $cantidad = (int)$producto['Cantidad'];
            $subtotal = (float)$producto['Precio'] * $cantidad;

            $detalleStmt->bindParam(':idVenta', $idVenta, PDO::PARAM_INT);
            $detalleStmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $detalleStmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $detalleStmt->bindParam(':subtotal', $subtotal, PDO::PARAM_STR);
            $detalleStmt->execute();

            $updateStockStmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $updateStockStmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $updateStockStmt->execute();
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Compra realizada con éxito.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al procesar la compra: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos.']);
}
?>
