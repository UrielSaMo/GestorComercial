<?php
session_start();

if (!isset($_SESSION['correo']) && $_SESSION['rol_id']  != null) {
    header('Location: ./inicioSesion.php');
    exit();
}
require_once './php/ConexionBD.php';
$connection = new ConexionDB();
$pdo = $connection->connect();

$sql = 'SELECT IDUsuario FROM usuario WHERE Correo = :correo';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':correo', $_SESSION['correo'], PDO::PARAM_STR);
$stmt->execute();

// Obtener el IDUsuario
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$idUsuario = $user ? $user['IDUsuario'] : null;


$sqltienda = 'SELECT IDTienda FROM usuario WHERE Correo = :correo';
$stmtienda = $pdo->prepare($sqltienda);
$stmtienda->bindParam(':correo', $_SESSION['correo'], PDO::PARAM_STR);
$stmtienda->execute();

//Obtener tienda 
$user1 = $stmtienda->fetch(PDO::FETCH_ASSOC);
$idtienda = $user1 ? $user1['IDTienda'] : null;


// Capturar los valores desde la URL
$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
$nombre = isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '';
$precio = isset($_GET['precio']) ? htmlspecialchars($_GET['precio']) : '';
$categoria = isset($_GET['categoria']) ? htmlspecialchars($_GET['categoria']) : '';
$stock = isset($_GET['stock']) ? htmlspecialchars($_GET['stock']) : '';
$estado = isset($_GET['estado']) ? htmlspecialchars($_GET['estado']) : '';


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Comercial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>

<body class="bg-light">


    <nav class="custom-navbar">
        <div class="navbar-container">
            <a class="navbar-brand" href="#"></a>
            <div class="navbar-menu">
                <a class="nav-item" href="#">
                    <?php
                    echo "<span class='user-text'>" . htmlspecialchars($_SESSION['correo']) . "</span>"; // Aqui para Mostrar IDUsuario
                    ?>
                    <img src="img/pexels-danxavier-1212984.jpg" alt="User" class="user-image">

                </a>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <div class="logo_content">
            <div class="menu_btn">
                <img src="icon/icons8-menu-30.png" alt=""><!-- Ícono de menú -->
            </div>
            <div class="logo">
                <img src="icon/icons8-shop-32.png">
                <div class="logo_name">GestorComercial</div>
            </div>
            <ul class="nav_list">
                <li>
                    <a href="carrito.php">
                        <img src="icon/icons8-person-24.png" alt="icono_persona">
                        <span class="links_name">Venta</span>
                    </a>
                </li>
                <li>
                    <a href="estadoInventario.php">
                        <img src="icon/icons8-product-24.png" alt="icono_producto"> <!-- Ícono de usuario -->
                        <span class="links_name">Estado de inventario</span>
                    </a>
                </li>
                <li>
                    <a href="php/cerrar_sesion.php">
                        <img src="icon/icons8-close-26.png" alt="icono_producto"> <!-- Ícono de usuario -->
                        <span class="links_name">Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Formulario -->
    <div class="d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <h2 class="text-center">Actualizar Producto</h2>
            <form action="php/procesarActualizacion.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio de producto</label>
                    <input type="number" class="form-control" id="precio" name="precio" value="<?php echo $precio; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo $categoria; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock del producto</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $stock; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" class="form-control" id="estado" name="estado" value="<?php echo $estado; ?>" required>
                </div>
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="ajax/ActualizacionCompleta.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>