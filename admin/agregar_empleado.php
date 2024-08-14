<?php
include 'db_config.php';

$message = "";
$messageType = "error"; // Default message type

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $esta_activo = isset($_POST['esta_activo']) ? 1 : 0;

    // Verificar si el usuario ya existe
    $checkUserSql = "SELECT COUNT(*) FROM empleados WHERE usuario = ?";
    $checkStmt = $conn->prepare($checkUserSql);
    $checkStmt->bind_param("s", $usuario);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        $message = "Error: El nombre de usuario ya existe. Por favor elige otro.";
    } else {
        // Inserta el nuevo empleado en la base de datos
        $sql = "INSERT INTO empleados (usuario, contraseña, nombre, apellidos, esta_activo) VALUES (?, ?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            $message = 'Error al preparar la consulta: ' . htmlspecialchars($conn->error);
        } else {
            // Enlazar parámetros
            $stmt->bind_param("ssssi", $usuario, $contraseña, $nombre, $apellidos, $esta_activo);

            if ($stmt->execute()) {
                $messageType = "success";
                $message = "Nuevo empleado agregado exitosamente.";
                // Redirigir después de 2 segundos
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'empleados.php';
                    }, 2000);
                </script>";
            } else {
                $message = "Error: " . htmlspecialchars($stmt->error);
            }

            $stmt->close();
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado</title>
    <link rel="stylesheet" href="css/styless.css">
    <style>
        .message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 5px;
            z-index: 1000;
            display: none;
        }
        .message.success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Agregar Nuevo Empleado</h1>
        </header>
        <main>
            <form action="agregar_empleado.php" method="post">
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" required>

                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" required>

                <label for="esta_activo">Activo:</label>
                <input type="checkbox" name="esta_activo" value="1">

                <button type="submit" class="button">Agregar Empleado</button>
            </form>
        </main>
    </div>
    <div class="message <?php echo $messageType; ?>" id="messageBox">
        <?php echo $message; ?>
    </div>
    <script>
        // Mostrar el mensaje si hay uno
        var messageBox = document.getElementById('messageBox');
        if (messageBox.innerHTML.trim() !== "") {
            messageBox.style.display = 'block';
        }
    </script>
</body>
</html>
