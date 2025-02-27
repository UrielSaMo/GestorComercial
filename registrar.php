<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Comercial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav style="background-color: #e7e7fb;" class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img src="icon/icons8-shop-32.png" alt="Logo" class="rounded me-2">
                <span style="color: black;">
                    Registrar tu tienda
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse"></div>
        </div>
    </nav>
    <!-- Formulario -->
    <div class="d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <form id="registroForm">
                <div class="mb-3">
                    <label for="tienda" class="form-label">Nombre de la tienda</label>
                    <input type="text" class="form-control" id="tienda" placeholder="Nombre de la tienda" name="tienda">
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Direccion</label>
                    <input type="text" class="form-control" id="direc" placeholder="Direccion fisica de la tienda" name="direccion">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="number" class="form-control" id="telefono" placeholder="Numero telefonico del propietario o administrador" name="telefono">
                </div>
                <div class="mb-3">
                    <label for="trabajadores" class="form-label">Número de trabajadores</label>
                    <input type="number" class="form-control" id="trabajadores" placeholder="Cantidad de trabajadores" name="trabajadores">
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de Administrador</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre completo" name="nombre">
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" placeholder="Apellidos" name="apellidos">
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electronico</label>
                    <input type="text" class="form-control" id="correo" placeholder="Correo Electronico" name="correo">
                </div>
                <div class="mb-3">
                    <label for="Password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" placeholder="Contraseña" name="pass">
                </div>
                <input type="hidden" name="action" value="register">
                <div class="d-flex justify-content-between">
                    <button type="reset" class="btn_color btn btn-secondary">Limpiar</button>
                    <button type="submit" class="btn btn_color">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="ajax/registroTodo.js"></script>
</body>

</html>