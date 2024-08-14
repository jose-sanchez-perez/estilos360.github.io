<?php
session_start();

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();
$errors = [];

// Obtener el token desde la URL
$token = isset($_GET['token']) ? $_GET['token'] : '';

if (!empty($_POST)) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (esNulo([$new_password, $confirm_password])) {
        $errors[] = "Debes llenar todos los campos";
    }

    if (!validaPassword($new_password, $confirm_password)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (count($errors) == 0 && !empty($token)) {
        $sql = $con->prepare("SELECT id, token_password FROM usuarios WHERE token_password LIKE ? LIMIT 1");
        $sql->execute([$token]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user_id = $row['id'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = $con->prepare("UPDATE usuarios SET password = ?, token_password = '', password_request = 0 WHERE id = ?");
            if ($sql->execute([$hashed_password, $user_id])) {
                echo '<div class="alert alert-success">Contraseña actualizada correctamente</div>';
            } else {
                $errors[] = "Error al actualizar la contraseña";
            }
        } else {
            $errors[] = "Token no válido";
        }
    } else {
        $errors[] = "Token no válido o no proporcionado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Cuenta - ESTILOS 360</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        .form-recover {
            max-width: 400px;
            margin: auto;
            padding: 15px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<main class="form-recover">
    <h2 class="text-center">Restablecer Contraseña</h2>
    <form action="" method="post" autocomplete="off">

        <div class="form-group">
            <label for="new_password">Nueva Contraseña</label>
            <input class="form-control" type="password" name="new_password" id="new_password" placeholder="Nueva Contraseña" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña</label>
            <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar Contraseña" required>
        </div>

        <div class="text-center">
            <button class="btn btn-primary" type="submit">Restablecer</button>
        </div>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
