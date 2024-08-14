<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $activo = 1; // Valor predeterminado para activo

    include 'db_config.php';

    // Preparar la consulta
    $stmt = $conn->prepare("INSERT INTO categorias (nombre, activo) VALUES (?, ?)");
    $stmt->bind_param("si", $nombre, $activo);

    if ($stmt->execute() === TRUE) {
        header("Location: categorias.php"); // Redirigir a categorias.php
        exit(); // Asegurar que el script se detenga después de la redirección
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Categoria - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Nueva Categoria</h1>
        </header>
        <main>
            <h2>Agregar Nueva Categoria</h2>
            <form action="nueva_categoria.php" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>
                <input type="submit" value="Guardar">
            </form>
        </main>
    </div>
</body>
</html>
