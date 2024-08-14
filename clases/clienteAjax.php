<?php
require '../config/database.php';
require_once 'clienteFunciones.php';

$datos = [];

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    $db = new Database();
    $con = $db->conectar();

    if ($action == 'existeUsuario') {
        $usuario = $_POST['usuario'];
        $datos['ok'] = usuarioExiste($usuario, $con);
    } elseif ($action == 'existeEmail') {
        $email = $_POST['email'];
        $datos['ok'] = emailExiste($email, $con);
    }
}

echo json_encode($datos);
?>
