<?php

function esNulo(array $parametros) {
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function emailExiste($email, $con) {
    $sql = $con->prepare("SELECT id FROM clientes WHERE email = ? LIMIT 1");
    $sql->execute([$email]);
    return $sql->fetchColumn() > 0;
}

function usuarioExiste($usuario, $con) {
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
    $sql->execute([$usuario]);
    return $sql->fetchColumn() > 0;
}

function mostrarMensajes(array $errors) {
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}

function login($usuario, $password, $con) {
    $sql = $con->prepare("SELECT id, usuario, password, nombre FROM admin WHERE usuario = ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            $_SESSION['user_type'] = 'admin'; // Suponiendo que 'admin' es el tipo de usuario correcto
            header('Location: categorias.php');
            exit;
        } else {
            return 'El usuario y/o contraseña son incorrectos';
        }
    }
    return 'El usuario y/o contraseña son incorrectos';
}

function solicitaPassword($user_id, $con) {
    $token = generarToken(); // Asumiendo que existe una función generarToken()
    $sql = $con->prepare("UPDATE usuarios SET token_password=?, password_request=1 WHERE id = ?");
    if ($sql->execute([$token, $user_id])) {
        return $token;
    }
    return null;
}

?>
