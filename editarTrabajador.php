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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trabajador</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body class="bg-light">

    <!-- Navbar -->
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
                    <a href="#Productos">
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
    <script src="js/script.js">
    </script>
    



    <!-- Formulario -->
     
    <div class="d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <div class="d-flex align-items-center mb-4">
                <img src="img/pexels-danxavier-1212984.jpg" alt="Foto del Trabajador" class="rounded-circle me-3" style="width: 110px; height: 110px; object-fit: cover;">
                <div>
                    <h5 class="mb-1">Alberto <span class="text-secondary">26 años</span></h5>
                    <p class="mb-0 text-muted">Giménez Alvarez</p>
                </div>
            </div>
            <form>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Estado de Trabajador</label>
                    <select name="estado" id="estado_trabajador" class="form-select">
                        <option value="" disabled selected>Seleccione una opcion</option>
                        <option value="activo">Activo</option>
                        <option value="suspendido">Suspendido</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="trabajadores" class="form-label">Edad de trabajador</label>
                    <input type="number" class="form-control" id="trabajadores" placeholder="Edad">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="submit" class="btn btn_color">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
