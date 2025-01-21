<?php
session_start();
require_once './php/ConexionBD.php';

class Usuario
{
    private $pdo;

    public function __construct()
    {
        $connection = new ConexionDB();
        $this->pdo = $connection->connect();
    }

    public function verificarSesion($correo, $rol)
    {

        if (!isset($_SESSION['correo']) || $_SESSION['rol_id'] != $rol) {
            header('Location: ./inicioSesion.php');
            exit();
        }
    }

    public function obtenerDatosPorCorreo($correo)
    {
        $sql = 'SELECT IDUsuario, Foto, IDTienda FROM usuario WHERE Correo = :correo';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerFotoBase64($foto)
    {
        return $foto ? 'data:image/jpeg;base64,' . base64_encode($foto) : 'default_image_path.jpg';
    }
}
