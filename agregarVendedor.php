<?php
session_start();
if (!isset($_SESSION['correo'])) {
    header('Location: ./inicioSesion.php');
    exit();
}
if ($_SESSION['role_id'] !== 1) {
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
    <link rel="stylesheet" href="css/sidebar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/vendedores y Producto.css">
    
   
    <title>Agregar Vendedor</title>
</head>
<body>

    <nav style="background-color: #e7e7fb;" class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="icon/icons8-shop-32.png" alt="Logo" class="rounded me-2" ><span style="color: black;">
                    GestorComercial
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
               
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
                    <a href="trabajador.html">
                        <img src="icon/icons8-person-24.png" alt="icono_persona">
                        <span class="links_name">Vendedores</span>
                    </a>
                </li>
                <li>
                    <a href="listaProductos.html">
                        <img src="icon/icons8-product-24.png" alt="icono_producto">
                        <span class="links_name">Productos</span>
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
    
    <script src="ajax/agregarVendedor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>
    
</body>
</html>