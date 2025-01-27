<?php
require_once './php/Usuario.php';
$usuario = new Usuario();
$usuario->verificarSesion($_SESSION['correo'],1 );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar2.css">
    <link rel="stylesheet" href="css/navbar2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/vendedores y Producto.css">
    <title>Agregar Vendedor</title>
</head>
<body>

    <nav class="custom-navbar">
        <div class="navbar-container">
            <span class="navbar-text mx-auto fw-bold fs-4">
                <img src="icon/icons8-shop-32.png" alt="User" class="user-image">
                <a href="estadoInventario.php" class="text-decoration-none text-dark">GestorComercial</a>
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
                    <a href="trabajador.php">
                        <img src="icon/icons8-person-24.png" alt="icono_persona">
                        <span class="links_name">Vendedores</span>
                    </a>
                </li>
                <li>
                    <a href="estadoInventario.php">
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
    
    <div class="contenido-gris">
        <div class="formulario-container">
            <h2>Agregar vendedor</h2>
            <form action="./php/agregarVendedor.php" method="post" id="agregarVendedor" enctype="multipart/form-data" >
                <div>
                    <label>Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div>
                    <label>Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" required>
                </div>
                <div>
                    <label>Correo</label>
                    <input type="text" id="correo" name="correo" required>
                </div>
                <div>
                    <label>Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <label>Foto del trabajador</label>
                    <input type="file" id="foto" name="foto">
                </div>
                <div class="buttons">
                    <button type="reset" class="limpiar-btn">Limpiar</button>
                    <button type="submit" class="agregar-btn">Registrar</button>
                    
                </div>
            </form>
        </div>
    </div>
    
    <script src="ajax/peticionModal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>
    
</body>
</html>