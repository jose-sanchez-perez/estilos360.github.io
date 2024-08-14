<?php
session_start();

// Verificar que se han enviado los detalles de la compra
if (!isset($_SESSION['detalles_compra'])) {
    header("Location: index.php");
    exit;
}

// Obtener los detalles de la compra desde la sesión
$detalles_compra = $_SESSION['detalles_compra'];
unset($_SESSION['detalles_compra']); // Limpiar los detalles de la compra después de mostrar
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h4>Compra Exitosa</h4>
            <p>Gracias por tu compra. Aquí están los detalles:</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles_compra['productos'] as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td><?php echo MONEDA . number_format($producto['subtotal'], 2, '.', ','); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total</strong></td>
                        <td><?php echo MONEDA . number_format($detalles_compra['total'], 2, '.', ','); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
