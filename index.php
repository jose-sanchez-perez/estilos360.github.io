<?php
session_start(); // Iniciar sesión

require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$idCategorias = $_GET['cat'] ?? null;
$order = $_GET['order'] ?? null;
$buscar = $_GET['q'] ?? null;

$orderQuery = '';
if ($order === 'precio_alto') {
    $orderQuery = 'ORDER BY precio DESC';
} elseif ($order === 'precio_bajo') {
    $orderQuery = 'ORDER BY precio ASC';
}

$params = [];
$query = "SELECT id, nombre, descuento, precio FROM productos WHERE activo=1";

if (!empty($buscar)) {
    $query .= " AND (nombre LIKE ?)";
    $params[] = "%$buscar%";
}

if ($idCategorias !== null) {
    $query .= " AND categoria = ?";
    $params[] = $idCategorias;
}

$query .= " $orderQuery";

$stmt = $con->prepare($query);
$stmt->execute($params);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlCategorias = $con->prepare("SELECT id, nombre FROM categorias WHERE activo=1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);
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
<main class="flex-shrink-0">
    <div class="container mt-4">
        <div class="row">
            <div class="col-3">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Categorias
                    </div>
                    <ul class="list-group">
                        <a href="index.php" class="list-group-item list-group-item-action">Todo</a>
                        <?php foreach ($categorias as $categoria): ?>
                            <a href="index.php?cat=<?php echo $categoria['id'];?>" class="list-group-item list-group-item-action <?php if($idCategorias == $categoria['id']) echo 'active'; ?>">
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="col-12 col-md-9">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select name="cbx-order" id="cbx-order" class="form-select form-select-sm" onchange="sortProducts()">
                            <option value="">Ordenar por</option>
                            <option value="precio_alto" <?php if ($order === 'precio_alto') echo 'selected'; ?>>Precios más altos</option>
                            <option value="precio_bajo" <?php if ($order === 'precio_bajo') echo 'selected'; ?>>Precios más bajos</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <?php foreach ($resultado as $producto): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card card-custom shadow-sm">
                                <?php
                                $id = $producto['id'];
                                $imagen = "images/productos/".$id.".jpg"; // Asumiendo que las imágenes tienen el nombre del ID del producto
                                if (!file_exists($imagen)) {
                                    $imagen = "images/no-photo.jpg";
                                }
                                ?>
                                <img src="<?= htmlspecialchars($imagen) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                    <?php if ($producto['descuento'] > 0):
                                        $precio_final = $producto['precio'] - ($producto['precio'] * $producto['descuento'] / 100);
                                    ?>
                                        <p class="card-text">
                                            <del><?= MONEDA . number_format($producto['precio'], 2, '.', ',') ?></del>
                                            <span class="discount-price"><?= MONEDA . number_format($precio_final, 2, '.', ',') ?></span>
                                            <span class="discount-price">(Descuento: <?= htmlspecialchars($producto['descuento']) ?>%)</span>
                                        </p>
                                    <?php else: ?>
                                        <p class="card-text"><?= MONEDA . number_format($producto['precio'], 2, '.', ',') ?></p>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="detalle.php?id=<?= $producto['id'] ?>&token=<?= hash_hmac('sha1', $producto['id'], KEY_TOKEN) ?>" class="btn btn-primary">Detalles</a>
                                        <a href="#" class="btn btn-success" onclick="addProducto(<?= $producto['id'] ?>, '<?= hash_hmac('sha1', $producto['id'], KEY_TOKEN) ?>')">Agregar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUbX39j5nQBOFqIvz8JoJ7+LG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
    function sortProducts() {
        const select = document.getElementById('cbx-order');
        const order = select.value;
        let url = 'index.php';

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('cat')) {
            url += '?cat=' + urlParams.get('cat');
            if (order) {
                url += '&order=' + order;
            }
        } else if (order) {
            url += '?order=' + order;
        }

        window.location.href = url;
    }

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
