<?php
class Vendedor {
    public $nombre;
    public $apellidos;
    public $correo;
    public $password;
    public $foto;

    public function __construct($nombre, $apellidos, $correo, $password, $foto) {
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
        $this->password = $password;
        $this->foto = $foto;
    }

    function procesarImagen() {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
            $archivo = mime_content_type($_FILES['foto']['tmp_name']);
            if (!in_array($archivo, $tiposPermitidos)) {
                echo json_encode(['success' => false, 'message' => 'Solo se permiten imágenes en formato JPG, JPEG y PNG.']);
                exit;
            }
            return file_get_contents($_FILES['foto']['tmp_name']);
        }
        return null;
    }
    
    // Función para verificar si el correo ya está registrado
    function emailExistente($correo) {
        $connection = new ConexionDB();
        $pdo = $connection->connect();
        $sqlCheckEmail = "SELECT Correo FROM usuario WHERE Correo = :correo AND rol_id = 2";
        $stmtCheckEmail = $pdo->prepare($sqlCheckEmail);
        $stmtCheckEmail->bindParam(':correo', $correo);
        $stmtCheckEmail->execute();
        return $stmtCheckEmail->rowCount() > 0;
    }
    
    // Función para registrar el vendedor
    function registrarVendedor($vendedor, $passwordHash, $fechaRegistro, $estado, $idTienda, $rol_id, $fotoContenido) {
        $connection = new ConexionDB();
        $pdo = $connection->connect();
        $sql = "INSERT INTO usuario (Nombre, Apellido, Correo, Contraseña, FechaRegistro, Foto, Estado, IDTienda, rol_id) 
                VALUES (:nombre, :apellidos, :correo, :password, :fechaRegistro, :foto, :estado, :idTienda, :rol_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $vendedor->nombre);
        $stmt->bindParam(':apellidos', $vendedor->apellidos);
        $stmt->bindParam(':correo', $vendedor->correo);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':fechaRegistro', $fechaRegistro);
        $stmt->bindParam(':foto', $fotoContenido, PDO::PARAM_LOB);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idTienda', $idTienda);
        $stmt->bindParam(':rol_id', $rol_id);
        return $stmt->execute();
    }



    public function obtenerCorreoActual($pdo, $userId) {
        $sql = "SELECT Correo FROM usuario WHERE IDUsuario = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function verificarCorreoExistente($pdo, $correo, $userId) {
        $sql = "SELECT COUNT(*) FROM usuario WHERE Correo = :correo AND IDUsuario != :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function actualizarVendedor($pdo, $passwordHash, $fotoContenido, $userId, $estado) {
        $sql = "UPDATE usuario SET Nombre = :nombre, Apellido = :apellidos, Estado = :estado";
    
        // Solo incluir la contraseña en la consulta si se proporcionó un nuevo valor
        if (!is_null($passwordHash)) {
            $sql .= ", Contraseña = :passwordHash";
        }
    
        // Solo incluir la foto en la consulta si se proporcionó un nuevo archivo
        if ($fotoContenido) {
            $sql .= ", Foto = :fotoContenido";
        }
    
        $sql .= " WHERE IDUsuario = :userId";
    
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':userId', $userId);
    
        if (!is_null($passwordHash)) {
            $stmt->bindParam(':passwordHash', $passwordHash);
        }
    
        if ($fotoContenido) {
            $stmt->bindParam(':fotoContenido', $fotoContenido, PDO::PARAM_LOB);
        }
    
        return $stmt->execute();
    }
    
}
?>
