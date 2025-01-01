<?php
require_once './ConexionBD.php';
session_start();
header('Content-Type: application/json; charset=utf-8'); // Asegurar que la respuesta sea JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de campos
    $correo = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
    $contraseña = trim($_POST['pass']);
    $rol = intval($_POST['rol_id']);

    if (!$correo || empty($contraseña) || !$rol) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    try {
        $connection = new ConexionDB();
        $pdo = $connection->connect();

        // Verificar si el usuario existe
        $sql = "SELECT * FROM usuario WHERE Correo = :correo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($contraseña, $user['Contraseña'])) {
            // Iniciar sesión
            $_SESSION['user_id'] = $user['IDUsuario'];
            $_SESSION['correo'] = $user['Correo'];
            $_SESSION['role_id'] = $user['rol_id'];

            // Verificar el rol y redirigir
            if ($user['rol_id'] === 1) { // Administrador
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión como Administrador exitoso.',
                    'redirectUrl' => '../GestorComercial/editarTrabajador.php'
                ]);
            } elseif ($user['rol_id'] === 2) { // Vendedor
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión como Vendedor exitoso.',
                    'redirectUrl' => '../GestorComercial/carrito.php'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'El rol seleccionado no es válido.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta o usuario no encontrado.']);
        }
    } catch (PDOException $e) {
        // Manejo de errores de conexión
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Manejo de errores generales
        echo json_encode(['success' => false, 'message' => 'Error inesperado: ' . $e->getMessage()]);
    }
} else {
    // Respuesta para solicitudes que no sean POST
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
