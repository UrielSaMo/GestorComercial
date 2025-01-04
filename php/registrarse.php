<?php
require_once './ConexionBD.php'; // Ajusta la ruta según tu estructura

header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['pass'] ?? '');
        $idTienda = intval(trim($_POST['claveUnica'] ?? ''));

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

        // Verificar si el correo ya está registrado
        $sqlCheckCorreo = "SELECT IDUsuario FROM usuario WHERE Correo = :correo";
        $stmtCheckCorreo = $conn->prepare($sqlCheckCorreo);
        $stmtCheckCorreo->bindParam(':correo', $correo);
        $stmtCheckCorreo->execute();
        if ($stmtCheckCorreo->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
            exit;
        }

        // Verificar si ClaveUnica existe en tienda
        $sqlCheckTienda = "SELECT IDTienda FROM tienda WHERE ClaveUnica = :claveUnica";
        $stmtCheckTienda = $conn->prepare($sqlCheckTienda);
        $stmtCheckTienda->bindParam(':claveUnica', $idTienda);
        $stmtCheckTienda->execute();
        if ($stmtCheckTienda->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'La ClaveUnica no es válida.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sqlInsert = "INSERT INTO usuario (Nombre, Apellido, Correo, Contraseña, Estado, ClaveUnica, rol_id) 
                      VALUES (:nombre, :apellidos, :correo, :password, 'Activo', :claveUnica, 1)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':nombre', $nombre);
        $stmtInsert->bindParam(':apellidos', $apellidos);
        $stmtInsert->bindParam(':correo', $correo);
        $stmtInsert->bindParam(':password', $hashedPassword);
        $stmtInsert->bindParam(':claveUnica', $idTienda);

        if ($stmtInsert->execute()) {
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
