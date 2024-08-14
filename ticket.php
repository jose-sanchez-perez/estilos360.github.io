<?php
session_start();
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id_compra = isset($_GET['id_compra']) ? $_GET['id_compra'] : 0;

$sql = $con->prepare("SELECT * FROM compra WHERE id = ?");
$sql->execute([$id_compra]);
$compra = $sql->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    echo "Compra no encontrada.";
    exit;
}

// Obtener el email del cliente basado en el id_cliente
$id_cliente = $compra['id_cliente'];
$sql = $con->prepare("SELECT email FROM clientes WHERE id = ?");
$sql->execute([$id_cliente]);
$cliente = $sql->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo "Cliente no encontrado.";
    exit;
}

$sql = $con->prepare("SELECT * FROM detalle_compra WHERE id_compra = ?");
$sql->execute([$id_compra]);
$detalles = $sql->fetchAll(PDO::FETCH_ASSOC);

if (!$detalles) {
    echo "Detalles de la compra no encontrados.";
    exit;
}

// Calcular el total de los subtotales
$total_subtotales = 0;
foreach ($detalles as $detalle) {
    $total_subtotales += $detalle['cantidad'] * $detalle['precio'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h4>Ticket de Compra</h4>
    <p><strong>ID Transacción:</strong> <?php echo htmlspecialchars($compra['id_transaccion']); ?></p>
    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($compra['fecha']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($compra['status']); ?></p>
    <p><strong>Email del Cliente:</strong> <?php echo htmlspecialchars($cliente['email']); ?></p> <!-- Mostrar el email del cliente -->
    <p><strong>ID Usuario:</strong> <?php echo htmlspecialchars($compra['id_cliente']); ?></p> <!-- Cambiado de 'id' a 'id_cliente' -->
    <p><strong>Total Calculado:</strong> <?php echo htmlspecialchars(MONEDA . number_format($total_subtotales, 2, '.', ',')); ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $detalle): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars(MONEDA . number_format($detalle['precio'], 2, '.', ',')); ?></td>
                    <td><?php echo htmlspecialchars(MONEDA . number_format($detalle['cantidad'] * $detalle['precio'], 2, '.', ',')); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-primary">Volver a la tienda</a>
</div>

</body>
</html>
