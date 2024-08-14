<?php
session_start(); // Asegúrate de iniciar la sesión

require '../config/config.php';

$response = ['ok' => false];

if (isset($_POST['id']) && isset($_POST['token'])) {
    $id = $_POST['id'];
    $token = $_POST['token'];

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token === $token_tmp) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = ['producto' => []];
        }

        if (!isset($_SESSION['carrito']['producto'][$id])) {
            $_SESSION['carrito']['producto'][$id] = 1;
        } else {
            $_SESSION['carrito']['producto'][$id]++;
        }

        // Count the number of unique products
        $response['numero'] = count($_SESSION['carrito']['producto']);
        $response['ok'] = true;
    }
}

echo json_encode($response);
?>
