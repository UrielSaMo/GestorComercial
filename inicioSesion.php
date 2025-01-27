<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Comercial</title>
    <link rel="stylesheet" href="css/inicioSesion.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">
        <h1 class="title">GESTORCOMERCIAL</h1>
        <div class="login-box">
            <img src="icon/icons8-shop-32.png" alt="Tienda" class="icon">
            <form id="loginForm">
                <div class="input-group">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" placeholder="Correo" required>
                </div>
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="pass" id="password" placeholder="Contraseña" required>
                </div>
                <div class="input-group">
                    <label for="rol">Rol</label>
                    <select name="rol_id" id="rol" class="form-select custom-select" required>
                        <option value="" disabled selected>Selecciona tu rol</option>
                        <option value="2">Vendedor</option>
                        <option value="1">Administrador</option>
                    </select>
                </div>
                <input type="hidden" name="action" value="login">
                <input type="submit" class="btn" value="Ingresar">
            </form>
        </div>
    </div>

    <script src="ajax/inicio.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>