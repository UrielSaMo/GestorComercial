<?php
require_once 'php/ConexionBD.php';
require_once './php/Usuario.php';
$usuario = new Usuario();
$usuario->verificarSesion($_SESSION['correo'],1 );

$connection = new ConexionDB();
$pdo = $connection->connect();

$idUsuario = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'ID no disponible';

$stmt = $pdo->prepare('SELECT Estado, Nombre, Apellido, Correo, Foto FROM usuario WHERE rol_id = 2 AND IDUsuario = :idUsuario');
$stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Convierte el BLOB a Base64 para mostrar la imagen
$fotoBase64 = base64_encode($user['Foto']);
$fotoSrc = is_null($user['Foto']) ? 'icon/perfil.png' : 'data:image/jpeg;base64,' . base64_encode($user['Foto']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trabajador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sidebar2.css">
    <link rel="stylesheet" href="css/navbar2.css">
</head>
<body class="bg-light">

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

    <div class="d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <div class="d-flex align-items-center mb-4">
                <img src="<?= $fotoSrc ?>" alt="Foto del Trabajador" class="rounded-circle me-3" style="width: 110px; height: 110px; object-fit: cover;">
                <div>
                    <h5 class="mb-1"><?= htmlspecialchars($user['Nombre']) ?></h5>
                    <p class="mb-0 text-muted"><?= htmlspecialchars($user['Apellido']) ?></p>
                    <p class="mb-0 text-muted">Estado: <?= htmlspecialchars($user['Estado']) ?></p>
                </div>
            </div>
            <form action="./php/actualizarVendedor.php" method="post" id="actualizarVendedor" enctype="multipart/form-data" >
                <div class="mb-3">

                    <input type="hidden" name="user_id" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>">

                    <label for="estado_trabajador" class="form-label">Estado de Trabajador</label>
                    <select name="estado" id="estado_trabajador" class="form-select" require>
                        <option value="" disabled selected>Seleccione un estado</option>
                        <option value="activo" <?= isset($user['Estado']) && $user['Estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= isset($user['Estado']) && $user['Estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($user['Nombre']) ?>" require>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos" value="<?= htmlspecialchars($user['Apellido']) ?>" require>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo" value="<?= htmlspecialchars($user['Correo']) ?>" require>
                </div>
                <div class="mb-3">
                    <label for="contraseña" class="form-label">Cambiar contraseña</label>
                    <input type="text" class="form-control" id="contraseña" name="password" placeholder="Por seguridad no se muestra la contraseña">
                </div>
                <div class="mb-3">
                    <label for="contraseña" class="form-label">Cambiar foto</label>
                    <input type="file" class="form-control" id="contraseña" name="foto">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="reset" class="btn btn-danger">Limpiar</button>
                    <button type="submit" class="btn btn_color">Enviar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="ajax/peticionModal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
