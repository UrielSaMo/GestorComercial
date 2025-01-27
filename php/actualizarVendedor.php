<?php
require_once './ConexionBD.php';
include './claseVendedor.php';

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

        if (!$correo) {
            echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
            exit;
        }

        // Crear una instancia de Vendedor
        $vendedor = new Vendedor($nombre, $apellidos, $correo, $password, null);

        // Procesar la imagen si se ha subido
        $fotoContenido = $vendedor->procesarImagen();

        $connection = new ConexionDB();
        $pdo = $connection->connect();

        // Obtener el correo actual del usuario
        $currentEmail = $vendedor->obtenerCorreoActual($pdo, $userId);

        // Verificar si el nuevo correo es diferente al actual
        if ($vendedor->verificarCorreoExistente($pdo, $correo, $userId)) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado por otro usuario.']);
            exit;
        }

        $fotoContenido = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoContenido = $vendedor->procesarImagen();
        }

        // Generar el hash de la contraseña solo si se envió una nueva
        $passwordHash = null;
        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        }

        // Actualizar los datos del vendedor
        if ($vendedor->actualizarVendedor($pdo, $passwordHash, $fotoContenido, $userId, $estado)) {
            echo json_encode([
                'success' => true,
                'message' => 'Vendedor actualizado exitosamente.',
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
