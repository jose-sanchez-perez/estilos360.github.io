<?php
include 'db_config.php';

$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Categorias</h1>
        </header>
        <main>
            <h2>Lista de Categorias</h2>
            <a href="nueva_categoria.php" class="button">Nueva Categoria</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
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
                            echo "<td>" . ($row["activo"] ? 'Sí' : 'No') . "</td>";
                            echo "<td>";
                            echo "<a href='editar_categoria.php?id=" . $row["id"] . "' class='button'>Editar</a> ";
                            echo "<a href='eliminar_categoria.php?id=" . $row["id"] . "' class='button' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta categoría?\");'>Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No hay categorías disponibles</td></tr>";
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
