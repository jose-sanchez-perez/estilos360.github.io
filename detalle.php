<?php
session_start();
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error: Missing ID or token';
    exit;
}

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

if ($token !== $token_tmp) {
    echo 'Error: Invalid token';
    exit;
}

$sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
$sql->execute([$id]);
if ($sql->fetchColumn() == 0) {
    echo 'Error: Product not found or inactive';
    exit;
}

$sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1");
$sql->execute([$id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo 'Error: Product details not found';
    exit;
}

$nombre = $row['nombre'];
$descripcion = nl2br($row['descripcion']); // Convertir saltos de línea a <br>
$precio = $row['precio'];
$descuento = $row['descuento'];
$precio_final = $precio - ($precio * $descuento / 100);

$image_path = "images/productos/$id.jpg";
$default_image = "images/no-photo.jpg";
$image_to_show = file_exists($image_path) ? $image_path : $default_image;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTILOS 360</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .product-image {
            max-width: 100%;
            height: auto;
        }
        .product-details {
            padding: 20px;
        }
        .discount-price {
            color: green;
        }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>

<main>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 order-md-1">
                <img src="<?php echo htmlspecialchars($image_to_show); ?>" alt="<?php echo htmlspecialchars($nombre); ?>" class="img-fluid product-image">
            </div>
            <div class="col-md-6 order-md-2 product-details">
                <h2><?php echo htmlspecialchars($nombre); ?></h2>
                <p><?php echo $descripcion; ?></p>
                <?php if ($descuento > 0): ?>
                    <p><del><?php echo htmlspecialchars(MONEDA . number_format($precio, 2)); ?></del></p>
                    <h4 class="discount-price"><?php echo htmlspecialchars(MONEDA . number_format($precio_final, 2)); ?> (Descuento: <?php echo htmlspecialchars($descuento); ?>%)</h4>
                <?php else: ?>
                    <h4><?php echo htmlspecialchars(MONEDA . number_format($precio, 2)); ?></h4>
                <?php endif; ?>
                <a href="#" class="btn btn-primary" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</a>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUbX39j5nQBOFqIvz8JoJ7+LG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    function addProducto(id, token) {
        let url = 'clases/carrito.php';
        let formData = new FormData();
        formData.append('id', id);
        formData.append('token', token);

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if (data.ok) {
                let elemento = document.getElementById("num_cart");
                elemento.innerHTML = data.numero;
            }
        });
    }
</script>
</body>
</html>
