<?php
include 'db_config.php';

$id = $_GET["id"];
$nombre = "";
$activo = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $activo = isset($_POST["activo"]) ? 1 : 0;

    $sql = "UPDATE categorias SET nombre='$nombre', activo='$activo' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Categoría actualizada exitosamente";
    } else {
        echo "Error al actualizar la categoría: " . $conn->error;
    }

    $conn->close();
    header("Location: categorias.php");
    exit;
} else {
    $sql = "SELECT * FROM categorias WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre = $row["nombre"];
        $activo = $row["activo"];
    } else {
        echo "Categoría no encontrada";
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
    <title>Editar Categoria - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Editar Categoria</h1>
        </header>
        <main>
            <h2>Editar Categoria</h2>
            <form action="editar_categoria.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required><br>
                <label for="activo">Activo:</label>
                <input type="checkbox" id="activo" name="activo" <?php if ($activo) echo 'checked'; ?>><br>
                <input type="submit" value="Guardar">
            </form>
        </main>
    </div>
</body>
</html>
