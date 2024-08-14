<?php
include 'db_config.php';

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Productos</h1>
        </header>
        <main>
            <h2>Lista de Productos</h2>
            <a href="nuevo_producto.php" class="button">Nuevo Producto</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Categoría</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["descripcion"] . "</td>";
                            echo "<td>" . $row["precio"] . "</td>";
                            echo "<td>" . $row["descuento"] . "</td>";
                            echo "<td>" . $row["categoria"] . "</td>";
                            echo "<td>" . ($row["activo"] ? 'Sí' : 'No') . "</td>";
                            echo "<td>";
                            echo "<a href='editar_producto.php?id=" . $row["id"] . "' class='button'>Editar</a> ";
                            echo "<a href='eliminar_producto.php?id=" . $row["id"] . "' class='button' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No hay productos disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
