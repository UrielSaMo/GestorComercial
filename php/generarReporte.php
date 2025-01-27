<?php
require_once './ConexionBD.php';
require_once '../vendor/autoload.php'; // Ruta del autoload de Composer

class ReportePDF
{
    private $pdo;

    public function __construct()
    {
        $conexion = new ConexionDB();
        $this->pdo = $conexion->connect();
    }

    public function obtenerDatosUsuario($correo)
    {
        $sql = 'SELECT IDTienda, (SELECT NombreTienda FROM tienda WHERE tienda.IDTienda = usuario.IDTienda) AS NombreTienda FROM usuario WHERE Correo = :correo';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generarReporteInventario($idTienda, $nombreTienda)
    {
        $sql = "SELECT IDProducto, Nombre, Precio, Categoria, Stock, Estado FROM productos WHERE IDTienda = :idTienda";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idTienda', $idTienda, PDO::PARAM_INT);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$productos) {
            throw new Exception('No hay productos para generar el reporte.');
        }

        $pdf = new FPDF();
        $pdf->AddPage();
        // Configurar márgenes
        $pdf->SetTopMargin(20);
        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(45);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Reporte de Inventario - Tienda: ' . $nombreTienda), 0, 1, 'C');
        $pdf->Ln(10);

        $this->agregarEncabezadoTabla($pdf, ['ID Producto', 'Producto', 'Precio', 'Categoría', 'Stock', 'Estado']);
        $this->agregarContenidoTabla($pdf, $productos);

        ob_end_clean(); // Limpia el buffer de salida
        $pdf->Output('I', 'Reporte_Inventario.pdf');
        exit;
    }

    public function generarReporteVentas($idTienda, $nombreTienda)
    {
        $sql = "SELECT dv.IDVenta, dv.IDProducto, dv.Cantidad, dv.Subtotal, v.FechaVenta
                FROM detalleventa dv
                INNER JOIN ventas v ON dv.IDVenta = v.IDVenta
                WHERE v.IDTienda = :idTienda";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idTienda', $idTienda, PDO::PARAM_INT);
        $stmt->execute();
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$ventas) {
            throw new Exception('No hay ventas para generar el reporte.');
        }

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Reporte de Ventas - Tienda: ' . $nombreTienda), 0, 1, 'C');
        $pdf->Ln(10);

        $this->agregarEncabezadoTabla($pdf, ['ID Venta', 'ID Producto', 'Cantidad', 'Subtotal', 'Fecha de Venta']);
        $this->agregarContenidoTabla($pdf, $ventas);

        ob_end_clean(); // Limpia el buffer de salida
        $pdf->Output('I', 'Reporte_Ventas.pdf');
        exit;
    }

    private function agregarEncabezadoTabla($pdf, $encabezados)
    {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(23, 162, 184);
        $pdf->SetTextColor(255, 255, 255);

        foreach ($encabezados as $encabezado) {
            $pdf->Cell(40, 10, utf8_decode($encabezado), 1, 0, 'C', true);
        }
        $pdf->Ln();
    }

    private function agregarContenidoTabla($pdf, $data)
    {
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(235, 235, 235);

        $fill = false;
        foreach ($data as $row) {
            foreach ($row as $column) {
                $pdf->Cell(40, 10, utf8_decode($column), 1, 0, 'C', $fill);
            }
            $pdf->Ln();
            $fill = !$fill;
        }
    }
}

// Ejemplo de uso
session_start();

if (!isset($_SESSION['correo'])) {
    die('Usuario no autenticado.');
}

try {
    $reporte = new ReportePDF();
    $datosUsuario = $reporte->obtenerDatosUsuario($_SESSION['correo']);

    if (!$datosUsuario) {
        throw new Exception('No se pudo obtener información del usuario.');
    }

    $idTienda = $datosUsuario['IDTienda'];
    $nombreTienda = $datosUsuario['NombreTienda'];

    if (!isset($_GET['tipo'])) {
        throw new Exception('Tipo de reporte no especificado.');
    }

    $tipo = $_GET['tipo'];
    if ($tipo === 'inventario') {
        $reporte->generarReporteInventario($idTienda, $nombreTienda);
    } elseif ($tipo === 'ventas') {
        $reporte->generarReporteVentas($idTienda, $nombreTienda);
    } else {
        throw new Exception('Tipo de reporte no válido.');
    }
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    die('Error: ' . $e->getMessage());
}
