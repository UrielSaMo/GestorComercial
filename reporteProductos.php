<?php
require_once './php/Usuario.php';
$usuario = new Usuario();
$usuario->verificarSesion($_SESSION['correo'],1 );

if (isset($_SESSION['detalleProductos'])) {
    $detalleProductos = $_SESSION['detalleProductos'];
    $productosExitosos = array_filter($detalleProductos, function ($producto) {
        return $producto['estatus'] === true;
    });

    $productosFallidos = array_filter($detalleProductos, function ($producto) {
        return $producto['estatus'] === false;
    });

    //unset($_SESSION['detalleProductos']);
} else {
    $productosExitosos = [];
    $productosFallidos = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos</title>
    <link rel="stylesheet" href="css/sidebar2.css">
    <link rel="stylesheet" href="css/navbar2.css">
    <link rel="stylesheet" href="css/reporteProductos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
              <img src="icon/icons8-menu-30.png" alt="">
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
                        <img src="icon/icons8-product-24.png" alt="icono_producto"> 
                        <span class="links_name">Productos</span>
                    </a>
                </li>
                <li>
                    <a href="./php/cerrar_sesion.php">
                        <img src="icon/icons8-close-26.png" alt="cerrar_icono"> 
                        <span class="links_name">Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="reporte-container">
        <p>Reporte de Productos</p>
        <p>Registrados Exitosamente</p>
        <table class="tabla-exitosos">
            <tr>
                <th>Nombre</th>
                <th>Razón</th>
            </tr>
            <?php foreach ($productosExitosos as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($producto['razon']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p>Error en el registro</p>
        <table class="tabla-fallidos">
            <tr>
                <th>Nombre</th>
                <th>Razón</th>
            </tr>
            <?php foreach ($productosFallidos as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($producto['razon']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <a href="./estadoInventario.php"><button type="submit" class="agregar-btn">Regresar</button></a>

    </div>

    <script src="js/script.js"></script>
</body>
</html>