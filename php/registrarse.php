<?php
require_once './ConexionBD.php'; // Ajusta la ruta según tu estructura

header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['pass'] ?? '');
        $idTienda = intval(trim($_POST['idTienda'] ?? ''));


        if (empty($nombre) || empty($apellidos) || empty($correo) || empty($password) || empty($idTienda)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
            exit;
        }

        $conexionDB = new ConexionDB();
        $conn = $conexionDB->connect();

        $sqlCheck = "SELECT IDUsuario FROM usuario WHERE Correo = :correo";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':correo', $correo);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuario (Nombre, Apellido, Correo, Contraseña, Estado, IDTienda, rol_id) 
                VALUES (:nombre, :apellidos, :correo, :password, 'Activo', :idTienda, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':idTienda', $idTienda);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
exit;
