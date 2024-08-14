<?php
include 'db_config.php';

// Consultar empleados
$sql = "SELECT * FROM empleados";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Empleados</h1>
        </header>
        <main>
            <h2>Lista de Empleados</h2>
            <a href="agregar_empleado.php" class="button">Nuevo Empleado</a>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
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
                            echo "<td>" . $row["usuario"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["apellidos"] . "</td>";
                            echo "<td>" . ($row["esta_activo"] ? 'Sí' : 'No') . "</td>";
                            echo "<td>";
                            echo "<a href='editar_empleado.php?id=" . $row["id"] . "' class='button'>Editar</a> ";
                            echo "<a href='eliminar_empleado.php?id=" . $row["id"] . "' class='button delete' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este empleado?\");'>Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay empleados disponibles</td></tr>";
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
