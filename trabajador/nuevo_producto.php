<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $descuento = $_POST["descuento"];
    $categoria = $_POST["categoria"];
    $activo = isset($_POST["activo"]) ? 1 : 0;

    $sql = "INSERT INTO productos (nombre, descripcion, precio, descuento, categoria, activo) VALUES ('$nombre', '$descripcion', '$precio', '$descuento', '$categoria', '$activo')";

    if ($conn->query($sql) === TRUE) {
        echo "Producto agregado exitosamente";
    } else {
        echo "Error al agregar el producto: " . $conn->error;
    }

    $conn->close();
    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Nuevo Producto</h1>
        </header>
        <main>
            <h2>Agregar Nuevo Producto</h2>
            <form action="nuevo_producto.php" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea><br>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required><br>
                <label for="descuento">Descuento:</label>
                <input type="number" id="descuento" name="descuento" required><br>
                <label for="categoria">Categoría:</label>
                <input type="number" id="categoria" name="categoria" required><br>
                <label for="activo">Activo:</label>
                <input type="checkbox" id="activo" name="activo"><br>
                <input type="submit" value="Guardar">
            </form>
        </main>
    </div>
</body>
</html>
