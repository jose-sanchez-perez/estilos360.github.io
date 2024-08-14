<?php
include 'db_config.php';

$id = $_GET["id"];
$nombre = "";
$descripcion = "";
$precio = 0;
$descuento = 0;
$categoria = 0;
$activo = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $descuento = $_POST["descuento"];
    $categoria = $_POST["categoria"];
    $activo = isset($_POST["activo"]) ? 1 : 0;

    $sql = "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', descuento='$descuento', categoria='$categoria', activo='$activo' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Producto actualizado exitosamente";
    } else {
        echo "Error al actualizar el producto: " . $conn->error;
    }

    $conn->close();
    header("Location: productos.php");
    exit;
} else {
    $sql = "SELECT * FROM productos WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre = $row["nombre"];
        $descripcion = $row["descripcion"];
        $precio = $row["precio"];
        $descuento = $row["descuento"];
        $categoria = $row["categoria"];
        $activo = $row["activo"];
    } else {
        echo "Producto no encontrado";
        $conn->close();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Editar Producto</h1>
        </header>
        <main>
            <h2>Editar Producto</h2>
            <form action="editar_producto.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required><br>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php echo $descripcion; ?></textarea><br>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" value="<?php echo $precio; ?>" required><br>
                <label for="descuento">Descuento:</label>
                <input type="number" id="descuento" name="descuento" value="<?php echo $descuento; ?>" required><br>
                <label for="categoria">Categoría:</label>
                <input type="number" id="categoria" name="categoria" value="<?php echo $categoria; ?>" required><br>
                <label for="activo">Activo:</label>
                <input type="checkbox" id="activo" name="activo" <?php if ($activo) echo 'checked'; ?>><br>
                <input type="submit" value="Guardar">
            </form>
        </main>
    </div>
</body>
</html>
