<?php
session_start(); // Iniciar sesión

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

// Asegúrate de que la sesión contiene un token
if (!isset($_SESSION['token'])) {
    header("Location: compras.php");
    exit;
}

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

// Verificar si los parámetros son válidos
if ($orden == null || $token == null || $token != $token_session) {
    header("Location: compras.php");
    exit;
}

// Conectar a la base de datos
$db = new Database();
$con = $db->conectar();
if (!$con) {
    die("Error al conectar a la base de datos");
}

$errors = [];

// Obtener los detalles de la compra
$sqlCompra = $con->prepare("SELECT id, id_transaccion, fecha, total FROM compra WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);

if (!$rowCompra) {
    header("Location: compras.php");
    exit;
}

$idCompra = $rowCompra['id'];

// Obtener los detalles de los productos comprados
$sqlDetalle = $con->prepare("SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
$sqlDetalle->execute([$idCompra]);
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
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card md-3">
                <div class="card-header">
                    <strong>Detalle de la compra</strong>
                </div>
                <div class="card-body">
                    <p><strong>Fecha: </strong><?php echo $rowCompra['fecha']; ?></p>
                    <p><strong>Orden: </strong><?php echo $rowCompra['id_transaccion']; ?></p>
                    <p><strong>Total: </strong><?php echo MONEDA . ' ' . number_format($rowCompra['total'], 2, '.', ','); ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8"> 
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                            $precio = $row['precio'];
                            $cantidad = $row['cantidad'];
                            $subtotal = $precio * $cantidad;
                        ?>
                        <tr>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo MONEDA . ' ' . number_format($precio, 2, '.', ','); ?></td>
                            <td><?php echo $cantidad; ?></td>
                            <td><?php echo MONEDA . ' ' . number_format($subtotal, 2, '.', ','); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php mostrarMensajes($errors); ?>
</div>

<main>
    <div class="container mt-4">
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUbX39j5nQBOFqIvz8JoJ7+LG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
