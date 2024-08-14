<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTILOS 360 - Método de Pago</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        .payment-button img {
            width: 100px;
            height: auto;
        }
        .payment-button {
            margin: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h4>Elija su forma de pago</h4>
    <div class="text-center">
        <!-- Botón de PayPal -->
        <a href="pago_paypal.php" class="payment-button">
            <img src="ruta/a/imagen_paypal.png" alt="PayPal">
        </a>
        <!-- Botón de BBVA -->
        <a href="pago_bbva.php" class="payment-button">
            <img src="ruta/a/imagen_bbva.png" alt="BBVA">
        </a>
    </div>
</div>

</body>
</html>
