<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Habilitar el registro de errores en un archivo para seguimiento
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');  // Especifica la ruta del archivo de log


include './ConexionBD.php';
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

    // Procesar la imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Validar el tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $fileType = mime_content_type($_FILES['foto']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Solo se permiten imágenes en formato JPG, JPEG y PNG.']);
        }else
        {
            $fotoTmpName = $_FILES['foto']['tmp_name'];
            $fotoContenido = file_get_contents($fotoTmpName);

            $connection = new ConexionDB();
            $pdo = $connection->connect();

            $sqlCheckEmail = "SELECT Correo FROM usuario WHERE Correo = :correo AND rol_id = 2";
            $stmtCheckEmail = $pdo->prepare($sqlCheckEmail);
            $stmtCheckEmail->bindParam(':correo', $correo);
            $stmtCheckEmail->execute();

            if ($stmtCheckEmail->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']);
            }else{
                $sql = "INSERT INTO usuario (Nombre, Apellido, Correo, Contraseña, FechaRegistro, Foto, Estado, IDTienda, rol_id) 
                VALUES (:nombre, :apellidos, :correo, :password, :fechaRegistro, :foto, :estado, :idTienda, :rol_id)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellidos', $apellidos);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':password', $passwordHash);
                $stmt->bindParam(':fechaRegistro', $fechaRegistro);
                $stmt->bindParam(':foto', $fotoContenido, PDO::PARAM_LOB);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':idTienda', $idTienda);
                $stmt->bindParam(':rol_id', $rol_id);

                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Vendedor registrado existosamente.',
                        'redirectUrl' => '../GestorComercial/trabajador.php'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al agregar al vendedor']);
                }
            }
        }
    } 

    
}
?>

