<?php
include 'db_config.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($password)) {
        $sql = "UPDATE empleados SET usuario = ?, contraseña = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $usuario, $password_hash, $id);
    } else {
        $sql = "UPDATE empleados SET usuario = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $usuario, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Empleado actualizado exitosamente.'); window.location.href='empleados.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar empleado: " . $stmt->error . "');</script>";
    }
}

$sql = "SELECT * FROM empleados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$empleado = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="css/styless.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Editar Empleado</h1>
        </header>
        <main>
            <form action="editar_empleado.php?id=<?php echo $empleado['id']; ?>" method="post">
                <div>
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($empleado['usuario']); ?>" required>
                </div>
                <div>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password">
                    <small>(Deja vacío si no deseas cambiar la contraseña)</small>
                </div>
                <div>
                    <button type="submit">Actualizar Empleado</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
