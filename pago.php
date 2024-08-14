<?php
session_start();

require 'config/config.php';
require 'config/database.php';

// Verificar si las constantes necesarias están definidas
if (!defined('MONEDA')) {
    die("Error: Las constantes necesarias no están definidas.");
}

$db = new Database();
$con = $db->conectar();

// Obtener productos del carrito de la sesión
$productos = isset($_SESSION['carrito']['producto']) ? $_SESSION['carrito']['producto'] : array();

$lista_carrito = array();
$total = 0;

if (!empty($productos)) {
    foreach ($productos as $id => $cantidad) {
        // Obtener detalles del producto desde la base de datos
        $sql = $con->prepare("SELECT id, nombre, descuento, precio FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $precio = $producto['precio'];
            $descuento = $producto['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $subtotal = $cantidad * $precio_desc;

            // Actualizar la cantidad y subtotal en el array del producto
            $producto['cantidad'] = $cantidad;
            $producto['subtotal'] = $subtotal;

            // Agregar producto actualizado a la lista del carrito
            $lista_carrito[] = $producto;

            // Calcular total general
            $total += $subtotal;
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTILOS 360</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .card-custom {
            width: 14rem;
        }
        .card-custom img {
            height: 150px;
            object-fit: contain;
        }
        .discount-price {
            color: green;
        }
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

<?php include 'menu.php'; ?>

<main>
    <div class="container mt-4">
        <div class="row">
            <div class="col-6">
                <h4>Formas de pago</h4>
                <div class="text-center">
                    <a href="pago_paypal.php" class="payment-button">
                        <img src="paypal.jpg" alt="Forma de pago 1">
                    </a>
                    <a href="pago_bbva.php" class="payment-button">
                        <img src="bbva.jpg" alt="Forma de pago 2">
                    </a>
                    <a href="pago_oxxo.php" class="payment-button">
                        <img src="oxxo.jpg" alt="Forma de pago 3">
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lista_carrito)): ?>
                                <tr><td colspan="2" class="text-center"><b>Lista vacía</b></td></tr>
                            <?php else: ?>
                                <?php foreach ($lista_carrito as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                        <td>
                                            <div id="subtotal_<?php echo $producto['id']; ?>"><?php echo htmlspecialchars(MONEDA . number_format($producto['subtotal'], 2, '.', ',')); ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right"><b>Total:</b></td>
                                    <td class="text-right">
                                        <p class="h3" id="total"><?php echo htmlspecialchars(MONEDA . number_format($total, 2, '.', ',')); ?></p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUbX39j5nQBOFqIvz8JoJ7+LG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
