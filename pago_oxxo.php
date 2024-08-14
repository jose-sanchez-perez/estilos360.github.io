<?php
require 'fpdf/fpdf.php';
require 'phpqrcode/qrlib.php';

// Verifica si la carpeta 'temp' existe, si no, la crea
if (!is_dir(__DIR__ . '/temp')) {
    mkdir(__DIR__ . '/temp', 0777, true);
}

// Datos de ejemplo
$total = 123.45;  // El total de la compra
$qr_filename = __DIR__ . '/temp/qr_' . uniqid() . '.png';

// Generar el código QR con el total a pagar
QRcode::png('Total a pagar: $' . $total, $qr_filename, 0, 10);

// Crear un nuevo documento PDF
$pdf = new FPDF();
$pdf->AddPage();

// Agregar la imagen centrada
$pdf->Image('oxxo.jpg', ($pdf->GetPageWidth() - 100) / 2, 20, 100);

// Agregar el código QR centrado
$pdf->Image($qr_filename, ($pdf->GetPageWidth() - 50) / 2, 100, 50, 50);

// Agregar mensaje de agradecimiento centrado
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetY(160);  // Establecer la posición vertical
$pdf->Cell(0, 10, 'Gracias por su compra', 0, 1, 'C');  // 'C' para centrar

// Generar la salida del PDF
$pdf->Output();
?>
