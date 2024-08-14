<?php
include 'db_config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Obtener datos del usuario
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Usuario no encontrado.";
        exit;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['usuario'];
        $password = encrypt($_POST['password'], $encryption_key);
        $activacion = $_POST['activacion'];
        $token = encrypt($_POST['token'], $encryption_key);
        $token_password = encrypt($_POST['token_password'], $encryption_key);
        $password_request = $_POST['password_request'];
        $id_cliente = $_POST['id_cliente'];

        // Actualizar datos del usuario
        $sql = "UPDATE usuarios SET usuario = ?, password = ?, activacion = ?, token = ?, token_password = ?, password_request = ?, id_cliente = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissiii", $usuario, $password, $activacion, $token, $token_password, $password_request, $id_cliente, $id);
        
        if ($stmt->execute()) {
            header("Location: usuarios.php");
            exit;
        } else {
            echo "Error al actualizar el usuario.";
        }
    }
} else {
    header("Location: usuarios.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - ESTILOS 360 ADMIN</title>
    <link rel="stylesheet" href="css/styless.css">
</head>


<body>
<?php include 'menu.php'; ?>
    <div class="main-content">
        <header>
            <h1>Editar Usuario</h1>
        </header>
        <main>
            <form method="post">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" value="<?php echo $row['usuario']; ?>" required>
                
                <label for="password">Contraseña:</label>
                <input type="text" id="password" name="password" value="<?php echo decrypt($row['password'], $encryption_key); ?>" required>
                
                <label for="activacion">Activación:</label>
                <input type="number" id="activacion" name="activacion" value="<?php echo $row['activacion']; ?>" required>
                
                <label for="token">Token:</label>
                <input type="text" id="token" name="token" value="<?php echo decrypt($row['token'], $encryption_key); ?>">
                
                <label for="token_password">Token Contraseña:</label>
                <input type="text" id="token_password" name="token_password" value="<?php echo decrypt($row['token_password'], $encryption_key); ?>">
                
                <label for="password_request">Password Request:</label>
                <input type="number" id="password_request" name="password_request" value="<?php echo $row['password_request']; ?>" required>
                
                <label for="id_cliente">ID Cliente:</label>
                <input type="number" id="id_cliente" name="id_cliente" value="<?php echo $row['id_cliente']; ?>" required>
                
                <button type="submit">Guardar Cambios</button>
            </form>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
