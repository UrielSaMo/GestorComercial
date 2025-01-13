<?php
require_once './ConexionBD.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (empty($_POST['estado'])) {
            echo json_encode(['success' => false, 'message' => 'Debe seleccionar un estado']);
            exit;
        }
        if (empty($_POST['nombre']) || empty($_POST['apellidos']) || empty($_POST['correo'])) {
            echo json_encode(['success' => false, 'message' => 'Los campos Nombre, Apellidos y Correo son obligatorios']);
            exit;
        }

        $estado = trim($_POST['estado']);
        $nombre = trim($_POST['nombre']);
        $apellidos = trim($_POST['apellidos']);
        $correo = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $userId = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
            exit;
        }

        $fotoContenido = null; // Valor predeterminado

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $fileType = mime_content_type($_FILES['foto']['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Solo se permiten imágenes en formato JPG, JPEG y PNG.']);
                exit;
            }

            $fotoTmpName = $_FILES['foto']['tmp_name'];
            $fotoContenido = file_get_contents($fotoTmpName);
        }

        $connection = new ConexionDB();
        $pdo = $connection->connect();

        // Obtener el correo actual del usuario
        $sqlCurrentEmail = "SELECT Correo FROM usuario WHERE IDUsuario = :userId";
        $stmtCurrentEmail = $pdo->prepare($sqlCurrentEmail);
        $stmtCurrentEmail->bindParam(':userId', $userId);
        $stmtCurrentEmail->execute();
        $currentEmail = $stmtCurrentEmail->fetchColumn();

        // Verificar si el nuevo correo es diferente al actual
        $updateCorreo = ($correo !== $currentEmail);

        // Iniciar la consulta base
        $sqlActualizar = "UPDATE usuario SET Nombre = :nombre, Apellido = :apellidos";

        // Agregar campos solo si es necesario
        if ($updateCorreo) {
            $sqlActualizar .= ", Correo = :correo";
        }
        if (!empty($passwordHash)) {
            $sqlActualizar .= ", Contraseña = :passwordHash";
        }
        if (!empty($fotoContenido)) {  
            $sqlActualizar .= ", Foto = :fotoContenido";
        }

        // Completar la consulta con la cláusula WHERE
        $sqlActualizar .= " WHERE IDUsuario = :userId";

        $stmtActualizar = $pdo->prepare($sqlActualizar);
        $stmtActualizar->bindParam(':nombre', $nombre);
        $stmtActualizar->bindParam(':apellidos', $apellidos);
        $stmtActualizar->bindParam(':userId', $userId);

        // Solo vincular el correo si se va a actualizar
        if ($updateCorreo) {
            $stmtActualizar->bindParam(':correo', $correo);
        }
        if (!empty($passwordHash)) {
            $stmtActualizar->bindParam(':passwordHash', $passwordHash);
        }
        if (!empty($fotoContenido)) {
            $stmtActualizar->bindParam(':fotoContenido', $fotoContenido, PDO::PARAM_LOB);
        }

        if ($stmtActualizar->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Vendedor actualizado existosamente.',
                'redirectUrl' => '../GestorComercial/trabajador.php'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en la actualización']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
exit;
?>
