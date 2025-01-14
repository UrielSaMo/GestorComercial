<?php
session_start();
if (!isset($_SESSION['correo'])) {
    header('Location: ./inicioSesion.php');
    exit();
}
if ($_SESSION['rol_id'] !== 1) {
    echo "<script>
     alert('Acceso Denegado. Solo administradores pueden acceder');";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Productos</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="custom-navbar">
        <div class="navbar-container">
            <span class="navbar-text mx-auto fw-bold fs-4">
                <img src="icon/icons8-shop-32.png" alt="User" class="user-image">
                <a href="carrito.php" class="text-decoration-none text-dark">GestorComercial</a>
            </span>
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
                    <a href="trabajador.html">
                        <img src="icon/icons8-person-24.png" alt="icono_persona">
                        <span class="links_name">Vendedores</span>
                    </a>
                </li>
                <li>
                    <a href="listaProductos.html">
                        <img src="icon/icons8-product-24.png" alt="icono_producto"> <!-- Ícono de usuario -->
                        <span class="links_name">Productos</span>
                    </a>
                </li>
                <li>
                    <a href="./php/cerrar_sesion.php">
                        <img src="icon/icons8-close-26.png" alt="cerrar_icono"> <!-- Ícono de usuario -->
                        <span class="links_name">Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bg-white container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div class="bg-white p-4 rounded shadow" style="width: 90%; max-width: 800px;">
            <!-- Botón y barra de búsqueda -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="agregarProducto.html"><button class="btn btn-light border">Registrar Nuevo Producto</button></a>
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control" placeholder="Buscar Producto">
                    <button class="btn btn-secondary">
                        <img src="icon/icons8-search-24.png" alt="Buscar">
                    </button>
                </div>
            </div>

            <!-- Tabla de productos -->
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Productos</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Leche Alpura</td>
                        <td>$26</td>
                        <td>Lácteos</td>
                        <td>15</td>
                        <td>Activo</td>
                        <td>
                            <a href="editarProducto.html">
                                <button class="btn btn-sm">
                                    <img src="icon/icons8-pencil-24.png" alt="">
                                </button>
                            </a>
                        </td>
                    </tr>
                    <tr class="table-light">
                        <td>Clarasol</td>
                        <td>$14</td>
                        <td>Limpieza</td>
                        <td>20</td>
                        <td>Activo</td>
                        <td>
                            <a href="editarProducto.html">
                                <button class="btn btn-sm">
                                    <img src="icon/icons8-pencil-24.png" alt="">
                                </button>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Tostadas</td>
                        <td>$28</td>
                        <td>Empaquetado</td>
                        <td>8</td>
                        <td>Suspendido</td>
                        <td>
                            <a href="editarProducto.html">
                                <button class="btn btn-sm">
                                    <img src="icon/icons8-pencil-24.png" alt="">
                                </button>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>