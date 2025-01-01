<?php
require_once './ConexionBD.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de campos
    $correo = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
    $contraseña = trim($_POST['pass']);
    $rol = intval($_POST['rol_id']);

    if (!$correo || empty($contraseña) || !$rol) {
        header("Location: ../ejemplo.php?error=Por favor completa todos los campos.");
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

            // Verificación del rol
            if ($rol === 1 && $user['rol_id'] === 1) {
                header('Location: ../editarTrabajador.php');
            } elseif ($rol === 2 && $user['rol_id'] === 2) {
                header('Location: ./vendedor/dashboard.php');
            } else {
                header("Location: ../inicioSesion.php?error=Acceso denegado: Rol no autorizado.");
            }
            exit;
        } else {
            header("Location: ../inicioSesion.php?error=Credenciales incorrectas.");
            exit;
        }
    } catch (PDOException $e) {
        // Manejo de errores de conexión
        header("Location: ../inicioSesion.php?error=Error en el sistema. Inténtalo más tarde.");
        exit;
    }
} else {
    header("Location: ../inicioSesion.php");
    exit;
}
