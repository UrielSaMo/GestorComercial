<?php

class ConexionDB
{
    private $host = "localhost";
    private $usuario = "root";
    private $contraseña = "";
    private $baseDatos = "servicio";

    public function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->baseDatos}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            return new PDO($dsn, $this->usuario, $this->contraseña, $options);
        } catch (\Throwable $th) {
            echo "Error en la conexion" . $th->getMessage();
            exit;
        }
    }
}
