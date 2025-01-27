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
    <link rel="stylesheet" href="css/agregarProducto.css">
    <link rel="stylesheet" href="css/navbar2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Agregar Producto</title>
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
                    <a href="listaProductos.php">
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
    
    <div class="formulario-container">
        <h2>Agregar Producto</h2>
        <form action="php/agregarProducto.php" method="post" id="agregarProducto">
            <table class="productos-tabla">
                <thead>
                    <tr>
                        <th>Productos</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Eliminar</th> 
                    </tr>
                </thead>
                <tbody id="productos-body">
                    <tr class="producto-row">
                        <td><input type="text" id="nombreProducto" name="nombreProducto[]"  placeholder="Nombre" required></td>
                        <td><input type="number" id="precio" name="precio[]"  placeholder="Precio" step="0.01" required></td>
                        <td><input type="text" id="categoria" name="categoria[]"  placeholder="Categoria" required></td>
                        <td><input type="number" id="stock" name="stock[]"  placeholder="Stock" required></td>
                        <td>
                            <select id="estado" name="estado[]">
                                <option value="Activo">Activo</option>
                                <option value="Suspendido">Suspendido</option>
                            </select>
                        </td>
                        <td><button type="button" class="eliminar-btn">Eliminar</button></td> <!-- Botón de eliminar -->
                    </tr>
                </tbody>
            </table>
            <div class="buttons">
               
                <button type="submit" class="agregar-btn">Agregar</button>
            </div>
        </form>

    </div>
    <script src="js/script.js"></script>
    <script src="js/agregarProductoFilas.js"></script>
    <script src="ajax/peticionModal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
