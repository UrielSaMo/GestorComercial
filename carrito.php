<?php
session_start();

if (!isset($_SESSION['correo']) || $_SESSION['role_id']  !=2) {
    header('Location: ./inicioSesion.php');
    exit();
}
require_once './php/ConexionBD.php';
$connection = new ConexionDB();
$pdo = $connection->connect();

$sql = 'SELECT id, correo FROM usuario';
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
 
 
 
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
            <a class="navbar-brand" href="#"></a>
            <div class="navbar-menu">
                <a class="nav-item" href="#">
                    <img src="img/pexels-danxavier-1212984.jpg" alt="User" class="user-image">
                    <span class="user-text">Vendedor</span>
                </a>
            </div>
        </div>
    </nav>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['correo']); ?> (<?= htmlspecialchars($_SESSION['role_id']); ?>)</p>

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
                    <a href="carrito.html">
                        <img src="icon/icons8-person-24.png" alt="icono_persona">
                        <span class="links_name">Venta</span>
                    </a>
                </li>
                <li>
                    <a href="estadoInventario.html">
                        <img src="icon/icons8-product-24.png" alt="icono_producto"> <!-- Ícono de usuario -->
                        <span class="links_name">Estado de inventario</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <!-- Sección de búsqueda y tabla -->
                <div class="col-md-8">
                    <!-- Barra de búsqueda -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Buscar Producto" aria-label="Buscar Producto">
                        <button class="btn btn-outline-secondary" type="button">
                            <img src="icon/icons8-search-24.png" alt="buscar">
                        </button>
                    </div>
    
                    <!-- Tabla de productos -->
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Productos</th>
                                <th>Precio</th>
                                <th class="cantidad-col">Cantidad</th>
                                <th>Total</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Leche</td>
                                <td>$26</td>
                                <td>
                                    <input type="number" class="form-control text-center" value="1" min="1">
                                </td>
                                <td>$26</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">
                                        <img src="icon/icons8-x-25.png" alt="eliminar">
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Clarasol</td>
                                <td>$14</td>
                                <td>
                                    <input type="number" class="form-control text-center" value="2" min="1">
                                </td>
                                <td>$28</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">
                                        <img src="icon/icons8-x-25.png" alt="eliminar">
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Tostadas</td>
                                <td>$28</td>
                                <td>
                                    <input type="number" class="form-control text-center" value="1" min="1">
                                </td>
                                <td>$28</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">
                                        <img src="icon/icons8-x-25.png" alt="eliminar">
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
    
                <!-- Sección del carrito -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Carrito</h5>
                            <div class="d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span>$82</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>IVA (16%)</span>
                                <span>$13.12</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span>$95.12</span>
                            </div>
                            <button class="btn btn_color w-100 mt-3">Confirmar compra</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
