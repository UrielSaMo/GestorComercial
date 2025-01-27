<?php
require_once './ConexionBD.php';
include './claseVendedor.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $correo = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $fechaRegistro = date('Y-m-d H:i:s');
    $estado = 1;
    $idTienda = $_SESSION['tienda_id'] ?? 1;
    $rol_id = 2;

    // Crear instancia del objeto Vendedor
    $vendedor = new Vendedor($nombre, $apellidos, $correo, $password, null);

    // Llamar al método procesarImagen desde la instancia de Vendedor
    $fotoContenido = $vendedor->procesarImagen();

    // Llamar al método emailExistente desde la instancia de Vendedor
    if ($vendedor->emailExistente($correo)) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']);
        exit;
    }

    // Llamar al método registrarVendedor desde la instancia de Vendedor
    if ($vendedor->registrarVendedor($vendedor, $passwordHash, $fechaRegistro, $estado, $idTienda, $rol_id, $fotoContenido)) {
        echo json_encode([
            'success' => true,
            'message' => 'Vendedor registrado exitosamente.',
            'redirectUrl' => '../GestorComercial/trabajador.php'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar al vendedor']);
    }
}


?>

