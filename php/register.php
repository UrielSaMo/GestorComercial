<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

include './ConexionBD.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tienda = trim($_POST['tienda'] ?? '');
        $trabajadores = intval(trim($_POST['trabajadores'] ?? ''));
        $direccion = trim($_POST['direccion'] ?? '');
        $telefono = intval(trim($_POST['telefono'] ?? ''));
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['pass'] ?? '');

        if (empty($nombre) || empty($apellidos) || empty($correo) || empty($password) || empty($tienda) || empty($trabajadores) || empty($direccion) || empty($telefono)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        $conexionDB = new ConexionDB();
        $conn = $conexionDB->connect();

        // Verificar si el correo ya está registrado
        $sqlCheckCorreo = "SELECT IDUsuario FROM usuario WHERE Correo = :correo";
        $stmtCheckCorreo = $conn->prepare($sqlCheckCorreo);
        $stmtCheckCorreo->bindParam(':correo', $correo);
        $stmtCheckCorreo->execute();
        if ($stmtCheckCorreo->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertar la tienda
        $sqlInsertTienda = "INSERT INTO tienda (NombreTienda, Direccion, Telefono, CantidadVendedores) VALUES (:tienda, :direccion, :telefono, :trabajadores)";
        $stmtInsertTienda = $conn->prepare($sqlInsertTienda);
        $stmtInsertTienda->bindParam(':tienda', $tienda);
        $stmtInsertTienda->bindParam(':direccion', $direccion);
        $stmtInsertTienda->bindParam(':telefono', $telefono);
        $stmtInsertTienda->bindParam(':trabajadores', $trabajadores);

        if (!$stmtInsertTienda->execute()) {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la tienda.']);
            exit;
        }

        // Obtener el ID de la tienda recién insertada
        $idTienda = $conn->lastInsertId();

        $sqlInsertUsuario = "INSERT INTO usuario (Nombre, Apellido, Correo, Contraseña, Estado, IDTienda, rol_id) 
                             VALUES (:nombre, :apellidos, :correo, :password, 'Activo', :IDTienda, 1)";
        $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
        $stmtInsertUsuario->bindParam(':nombre', $nombre);
        $stmtInsertUsuario->bindParam(':apellidos', $apellidos);
        $stmtInsertUsuario->bindParam(':correo', $correo);
        $stmtInsertUsuario->bindParam(':password', $hashedPassword);
        $stmtInsertUsuario->bindParam(':IDTienda', $idTienda);
        
        if ($stmtInsertUsuario->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tienda registrada exitosamente y Administrador agregado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el administrador.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
