<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estilos_360";
$encryption_key = "your_secret_key"; // Cambia esta clave por una segura y guárdala de forma segura

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function encrypt($data, $key) {
    $iv = substr(hash('sha256', '1234567890123456'), 0, 16);
    return openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
}

function decrypt($data, $key) {
    $iv = substr(hash('sha256', '1234567890123456'), 0, 16);
    return openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);
}
?>
