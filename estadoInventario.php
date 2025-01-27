<?php

require_once './php/Usuario.php';
$usuario = new Usuario();
$rol = $usuario->rolSesion();
if (!isset($_SESSION['correo'])) {
    header('Location: ./inicioSesion.php');
    exit();
}



$datosUsuario = $usuario->obtenerDatosPorCorreo($_SESSION['correo']);
$idUsuario = $datosUsuario['IDUsuario'] ?? null;
$idTienda = $datosUsuario['IDTienda'] ?? null;
$fotoBase64 = $usuario->obtenerFotoBase64($datosUsuario['Foto'] ?? null);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/estadoInventario.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Home</title>
</head>

<body>
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
                <?php
                    if($rol == 1){
                ?>
                <li>
                    <a href="trabajador.php">
                        <img src="icon/icons8-person-24.png" alt="icono_persona">
                        <span class="links_name">Vendedores</span>
                    </a>
                </li>
                <li>
                    <a href="estadoInventario.php">
                        <img src="icon/icons8-product-24.png" alt="icono_producto"> <!-- Ícono de usuario -->
                        <span class="links_name">Estado del Inventario</span>
                    </a>
                </li>
                <li>
                    <a href="./php/cerrar_sesion.php">
                        <img src="icon/icons8-close-26.png" alt="cerrar_icono"> <!-- Ícono de usuario -->
                        <span class="links_name">Cerrar Sesión</span>
                    </a>
                </li>
                <?php
                    }
                ?>
                <?php
                if($rol == 2){
                ?>
                <li>
                    <a href="carrito.php">
                        <img src="icon/icons8-person-24.png" alt="icono_persona">
                        <span class="links_name">Venta</span>
                    </a>
                </li>
                <li>
                    <a href="estadoInventario.php">
                        <img src="icon/icons8-product-24.png" alt="icono_producto"> <!-- Ícono de usuario -->
                        <span class="links_name">Estado del Inventario</span>
                    </a>
                </li>
                <li>
                    <a href="./php/cerrar_sesion.php">
                        <img src="icon/icons8-close-26.png" alt="cerrar_icono"> <!-- Ícono de usuario -->
                        <span class="links_name">Cerrar Sesión</span>
                    </a>
                </li>
                <?php
                }
                ?>
            </ul>
            
        </div>
    </div>
    <nav class="custom-navbar">
        <div class="navbar-container">
            <span class="navbar-text mx-auto fw-bold fs-4">
                <img src="icon/icons8-shop-32.png" alt="User" class="user-image">
                <a href="estadoInventario.php" class="text-decoration-none text-dark">GestorComercial</a>
            </span>
            <div class="navbar-menu">
                <a class="nav-item" href="#">
                    <?php
                    echo "<span class='user-text'>" . htmlspecialchars($_SESSION['correo']) . "</span>"; // Aqui para Mostrar IDUsuario
                    ?>
                     <img src="<?php echo htmlspecialchars($fotoBase64); ?>" alt="User" class="user-image">
                </a>
            </div>
        </div>
    </nav>
    <div class="productos-container">
        <h2>Estado del Inventario</h2>
        <header>
            <div class="buscar-filtros">
                <div class="buscar-contenedor">
                    <input type="text" id="buscar-producto" placeholder="Buscar Producto" class="buscar-input">
                    <button class="buscar-btn" disabled>
                        <img src="icon/icons8-search-50.png" alt="Buscar" class="buscar-icono"> <!-- Imagen de la lupa -->
                    </button>
                </div>
                <select id="categoria-select" class="categoria-select">
                    <option value="">Sin filtro</option>
                    <option value="Bebidas">Bebidas</option>
                    <option value="Limpieza">Limpieza</option>
                    <option value="Empaquetado">Empaquetado</option>
                </select>
            </div>
            <?php
            if($rol == 1){
            ?>
            <button id="reporte-btn" class="btn btn-primary mt-3">Generar Reporte de Inventario</button>
            <button id="reporte_venta_btn" class="btn btn-primary mt-3">Generar Reporte de ventas</button>
            <a href="agregarProducto.php"><button id="reporte_venta_btn" class="btn btn-primary mt-3">Agregar Producto</button></a>
            <?php
            }
            ?>
        </header>

        <table class="productos-tabla">
            <thead>
                <tr>
                    <th>Id del Producto</th>
                    <th>Productos</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody id="productos-body">
                <tr>

                </tr>
            </tbody>
        </table>
    </div>

    <script src="js/script.js">
    </script>
    <script src="js/reporteInv.js" ></script>
    <script src="ajax/obtenerProductos.js">
    </script>


</body>

</html>