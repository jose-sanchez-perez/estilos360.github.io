<?php
session_start(); // Iniciar sesión

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_cliente'])) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$con = $db->conectar();
if (!$con) {
    die("Error al conectar a la base de datos");
}

$token = generarToken();
$_SESSION['token'] = $token;
$idCliente = $_SESSION['user_cliente'];

// Preparar la consulta para obtener las compras del cliente
$sql = $con->prepare("SELECT id_transaccion, fecha, status, total FROM compra WHERE id_cliente = ? ORDER BY DATE(fecha) DESC");
$sql->execute([$idCliente]);

$compras = $sql->fetchAll(PDO::FETCH_ASSOC); // Obtener todas las compras
$errors = [];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTILOS 360 - Mis Compras</title>
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
    <h4>Mis compras</h4>
    <hr>

    <?php if (count($compras) > 0): ?>
        <?php foreach ($compras as $compra): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <?php echo htmlspecialchars($compra['fecha']); ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Folio: <?php echo htmlspecialchars($compra['id_transaccion']); ?></h5>
                    <p class="card-text">Total: <?php echo htmlspecialchars($compra['total']); ?></p>
                    <a href="compra_detalle.php?orden=<?php echo htmlspecialchars($compra['id_transaccion']); ?>&token=<?php echo $token; ?>" class="btn btn-primary">Ver compra</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No has realizado compras aún.</p>
    <?php endif; ?>
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
