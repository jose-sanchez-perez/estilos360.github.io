<?php
include 'db_config.php';

$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Clientes</h1>
        </header>
        <main>
            <h2>Lista de Clientes</h2>
            <a href="nuevo_cliente.php" class="button">Nuevo Cliente</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estatus</th>
                        <th>Fecha Alta</th>
                        <th>Fecha Modificación</th>
                        <th>Fecha Baja</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["nombres"] . "</td>";
                            echo "<td>" . $row["apellidos"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["telefono"] . "</td>";
                            echo "<td>" . ($row["estatus"] ? 'Activo' : 'Inactivo') . "</td>";
                            echo "<td>" . $row["fecha_alta"] . "</td>";
                            echo "<td>" . $row["fecha_modifica"] . "</td>";
                            echo "<td>" . $row["fecha_baja"] . "</td>";
                            echo "<td>";
                            echo "<a href='editar_cliente.php?id=" . $row["id"] . "' class='button'>Editar</a> ";
                            echo "<a href='eliminar_cliente.php?id=" . $row["id"] . "' class='button' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este cliente?\");'>Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>No hay clientes disponibles</td></tr>";
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
