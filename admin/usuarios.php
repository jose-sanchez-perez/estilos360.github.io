<?php
include 'db_config.php';

$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Usuarios</h1>
        </header>
        <main>
            <h2>Lista de Usuarios</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Contraseña</th>
                        <th>Activación</th>
                        <th>Token</th>
                        <th>Token Contraseña</th>
                        <th>Password Request</th>
                        <th>ID Cliente</th>
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
                            echo "<td>" . decrypt($row["password"], $encryption_key) . "</td>";
                            echo "<td>" . $row["activacion"] . "</td>";
                            echo "<td>" . decrypt($row["token"], $encryption_key) . "</td>";
                            echo "<td>" . decrypt($row["token_password"], $encryption_key) . "</td>";
                            echo "<td>" . $row["password_request"] . "</td>";
                            echo "<td>" . $row["id_cliente"] . "</td>";
                            echo "<td>";
                            echo "<a href='editar_usuario.php?id=" . $row["id"] . "' class='button'>Editar</a> ";
                            echo "<a href='eliminar_usuario.php?id=" . $row["id"] . "' class='button' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");'>Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No hay usuarios disponibles</td></tr>";
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
