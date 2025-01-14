<?php
session_start();

if (!isset($_SESSION['correo']) || $_SESSION['rol_id']  != 2) {
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

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="custom-navbar">
        <div class="navbar-container">
            <span class="navbar-text mx-auto fw-bold fs-4">
                <img src="icon/icons8-shop-32.png" alt="User" class="user-image">
                <a href="carrito.php" class="text-decoration-none text-dark">GestorComercial</a>
            </span>
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

    <div class="container vh-100 d-flex align-items-center justify-content-center ">
        <div class="container">
            <div class="row">
                <!-- Sección de búsqueda y tabla -->
                <div class="col-md-8">
                    <!-- Barra de búsqueda -->
                    <div class="input-group mb-3">
                        <input type="text" id="search-bar" class="form-control" placeholder="Buscar Producto" aria-label="Buscar Producto">
                        <button class="btn btn-outline-secondary" id="search-btn" type="button">
                            <img src="icon/icons8-plus-24.png" alt="buscar">
                        </button>
                    </div>


                    <!-- Tabla de productos -->
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th >Id del producto</th>
                                <th>Productos</th>
                                <th>Precio</th>
                                <th class="cantidad-col">Cantidad</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">
                            <!-- Productos dinámicos se renderizan aquí -->
                        </tbody>
                    </table>

                </div>

                <!-- Sección del carrito -->
                <div class="col-md-4 container">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Carrito</h5>
                            <form action="./php/compra.php" method="POST">
                                <div class="d-flex justify-content-between subtotal">
                                    <span>Subtotal</span>
                                    <span>$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between iva">
                                    <span>IVA (16%)</span>
                                    <span>$0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold total">
                                    <span>Total</span>
                                    <span>$0.00</span>
                                </div>

                                <!-- Campos ocultos para enviar datos de la compra -->
                                <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $idUsuario; ?>"> 
                                <input type="hidden" id="idTienda" name="idTienda" value="<?php echo $idtienda; ?>"> 
                                <input type="hidden" id="subtotal" name="total" value="">
                                <input type="hidden" id="total" name="total" value="">
                                <input type="hidden" name="productos" id="productosInput"> <!-- Será llenado con JS -->
                                <button type="submit" name="confirmarCompra" id="confirmarCompraBtn" class="btn btn_color w-100 mt-3">Confirmar compra</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>
    <script src="ajax/fetchProduct.js"></script>
    <script src="ajax/productosInput.js"></script>
    <script src="ajax/confirmarCompra.js"></script>

</body>

</html>