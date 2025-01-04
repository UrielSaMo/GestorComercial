<?php

require_once './ConexionBD.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tienda = trim($_POST['tienda'] ?? '');
        $trabajadores = intval(trim($_POST['trabajadores'] ?? ''));
        $direccion = trim($_POST['direccion'] ?? '');
        $telefono = intval(trim($_POST['telefono'] ?? ''));


        if (empty($tienda) || empty($trabajadores) || empty($direccion) || empty($telefono)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);

            exit;
        }

        $conexionDB = new ConexionDB();
        $conn = $conexionDB->connect();

        $sqlCheck = "SELECT IDTienda FROM tienda WHERE NombreTienda = :tienda";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':tienda', $tienda);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'El nombre de la tienda ya está registrado.']);
            exit;
        }

        $ClaveUnica = random_int(10000, 99999);

        $sql = "INSERT INTO tienda (ClaveUnica, NombreTienda, Direccion, Telefono, CantidadVendedores)
                Values (:claveTienda, :tienda, :direccion, :telefono, :trabajadores)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':claveTienda', $ClaveUnica);
        $stmt->bindParam(':tienda', $tienda);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':trabajadores', $trabajadores);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tienda registrada exitosamente con Clave Unica: <strong>' . $ClaveUnica . '</strong>']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la tienda.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Metodo no permitido.']);
    }
} catch (PDOException $th) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $th->getMessage()]);
}
exit;
