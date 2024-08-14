<?php

function esNulo(array $parametros) {
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function esEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validaPassword($password, $repassword) {
    return strcmp($password, $repassword) === 0;
}

function generarToken() {
    return md5(uniqid(mt_rand(), true));
}

function registrar(array $datos, $con) {
    $sql = $con->prepare("INSERT INTO clientes (nombres, apellidos, email, telefono, estatus, fecha_alta) VALUES (?, ?, ?, ?, 1, now())");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

function registrarUsuario(array $datos, $con) {
    $sql = $con->prepare("INSERT INTO usuarios (usuario, password, token, id_cliente) VALUES (?, ?, ?, ?)");
    return $sql->execute($datos);
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

function mostrarMensajes(array $errors){
    if(count($errors) > 0){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
        echo '<ul>';
        foreach($errors as $error){
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}

function login($usuario, $password, $con, $proceso){
    $sql = $con->prepare("SELECT id, usuario, password, id_cliente FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if($row = $sql->fetch(PDO::FETCH_ASSOC)){
        if(esActivo($usuario, $con)){
            if (password_verify($password, $row['password'])){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['usuario'];
                $_SESSION['user_cliente'] = $row['id_cliente'];
                if($proceso == 'pago'){
                    header("Location: checkout.php"); 
                } else {
                header("Location: index.php");
                }
                exit;
            } else {
                return 'El usuario y/o contraseña son incorrectos';
            }
        } else {
            return 'El usuario no ha sido activado';
        }
    }
    return 'El usuario y/o contraseña son incorrectos';
}

function esActivo($usuario, $con){
    $sql = $con->prepare("SELECT activacion FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    return $row['activacion'] == 0;
}

function solicitaPassword($user_id, $con){
    $token = generarToken();
    $sql = $con->prepare("UPDATE usuarios SET token_password=?, password_request=1 WHERE id = ?");
    if($sql->execute([$token, $user_id])){
        return $token;
    }
    return null;
}

?>
