<?php
    session_start();
    if (!isset($_SESSION['correo'])) {
        header('Location: ./inicioSesion.php');
        exit();
    }

    if ($_SESSION['role_id'] !== 1) {
        echo "<script>
            alert('Acceso Denegado. Solo administradores pueden acceder');
            window.location.href = './inicioSesion.php';
        </script>";
        exit();
    }

    require_once 'php/ConexionBD.php';

    $connection = new ConexionDB();
    $pdo = $connection->connect();
    $idTienda = $_SESSION['tienda_id'];

    $stmt = $pdo->prepare('SELECT IDUsuario, Nombre, Apellido, Foto FROM usuario WHERE rol_id = 2 AND IDTienda = :idTienda');
    $stmt->bindParam(':idTienda', $idTienda, PDO::PARAM_INT);
    $stmt->execute();
    $vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/trabajador.css">

    <title>Home</title>
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
    

    <div class="titulo-container">
        <h2>Lista de Vendedores</h2>
    </div>
    
    <div class="registrar-vendedor-container">
        <a href="agregarVendedor.php"><button class="registrar-btn">Registrar Nuevo Vendedor</button></a>
    </div>

    <div class="trabajadores-container">
        <?php foreach ($vendedores as $vendedor): ?>
            <div class="trabajador-card">
                <?php if ($vendedor['Foto']): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($vendedor['Foto']) ?>" alt="Foto de <?= htmlspecialchars($vendedor['Nombre']) ?>">
                <?php else: ?>
                    <img src="icon/perfil.png" alt="Foto predeterminada">
                <?php endif; ?>
                <div class="info">
                    <h3><?= htmlspecialchars($vendedor['Nombre']) ?></h3>
                    <p><?= htmlspecialchars($vendedor['Apellido']) ?></p>
                    <a href="editarTrabajador.php?id=<?= htmlspecialchars($vendedor['IDUsuario']) ?>"><button class="editar-btn">Editar Trabajador</button></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script src="js/script.js">
    </script>
    
</body>
</html>