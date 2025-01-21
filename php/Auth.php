<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include './ConexionBD.php';
session_start();

class Auth {
    private $pdo;

    public function __construct() {
        $connection = new ConexionDB();
        $this->pdo = $connection->connect();
    }

    public function login($correo, $contraseña, $rol) {
        header('Content-Type: application/json; charset=utf-8');

        if (!$correo || empty($contraseña) || !$rol) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        try {
            $sql = "SELECT * FROM usuario WHERE Correo = :correo";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':correo' => $correo]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($contraseña, $user['Contraseña'])) {
                if ($user['rol_id'] == $rol) {
                    $_SESSION['user_id'] = $user['IDUsuario'];
                    $_SESSION['correo'] = $user['Correo'];
                    $_SESSION['rol_id'] = $user['rol_id'];
                    $_SESSION['tienda_id'] = $user['IDTienda'];

                    if ($rol === 1) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Inicio de sesión como Administrador exitoso.',
                            'redirectUrl' => '../GestorComercial/editarTrabajador.php'
                        ]);
                    } elseif ($rol === 2) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Inicio de sesión como Vendedor exitoso.',
                            'redirectUrl' => '../GestorComercial/carrito.php'
                        ]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'El rol seleccionado no coincide con el registrado.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta o usuario no encontrado.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error inesperado: ' . $e->getMessage()]);
        }
    }

    public function register($tienda, $trabajadores, $direccion, $telefono, $nombre, $apellidos, $correo, $password) {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($nombre) || empty($apellidos) || empty($correo) || empty($password) || empty($tienda) || empty($trabajadores) || empty($direccion) || empty($telefono)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        try {
            $sqlCheckCorreo = "SELECT IDUsuario FROM usuario WHERE Correo = :correo";
            $stmtCheckCorreo = $this->pdo->prepare($sqlCheckCorreo);
            $stmtCheckCorreo->bindParam(':correo', $correo);
            $stmtCheckCorreo->execute();
            if ($stmtCheckCorreo->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
                exit;
            }
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sqlInsertTienda = "INSERT INTO tienda (NombreTienda, Direccion, Telefono, CantidadVendedores) VALUES (:tienda, :direccion, :telefono, :trabajadores)";
            $stmtInsertTienda = $this->pdo->prepare($sqlInsertTienda);
            $stmtInsertTienda->bindParam(':tienda', $tienda);
            $stmtInsertTienda->bindParam(':direccion', $direccion);
            $stmtInsertTienda->bindParam(':telefono', $telefono);
            $stmtInsertTienda->bindParam(':trabajadores', $trabajadores);

            if (!$stmtInsertTienda->execute()) {
                echo json_encode(['success' => false, 'message' => 'Error al registrar la tienda.']);
                exit;
            }

            $idTienda = $this->pdo->lastInsertId();

            $sqlInsertUsuario = "INSERT INTO usuario (Nombre, Apellido, Correo, Contraseña, Estado, IDTienda, rol_id) 
                                 VALUES (:nombre, :apellidos, :correo, :password, 'Activo', :IDTienda, 1)";
            $stmtInsertUsuario = $this->pdo->prepare($sqlInsertUsuario);
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
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
        }
    }
}

// Ejemplo de uso
$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'login') {
            $auth->login($_POST['correo'], $_POST['pass'], intval($_POST['rol_id']));
        } elseif ($_POST['action'] === 'register') {
            $auth->register($_POST['tienda'], intval($_POST['trabajadores']), $_POST['direccion'], intval($_POST['telefono']), $_POST['nombre'], $_POST['apellidos'], $_POST['correo'], $_POST['pass']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no especificada.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
}
?>