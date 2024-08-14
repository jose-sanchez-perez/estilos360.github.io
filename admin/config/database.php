<?php

class Database {
    private $hostname = "localhost";
    private $database = "estilos_360";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    function conectar() {
        try {
            $conexion = "mysql:host=" . $this->hostname . ";dbname=" . $this->database . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            // Accediendo a las propiedades de la clase con $this->
            $pdo = new PDO($conexion, $this->username, $this->password, $options);

            return $pdo;
        } catch (PDOException $e) {
            echo 'Error conexión: ' . $e->getMessage();
            exit;
        }
    }
}

// Ejemplo de uso
$db = new Database();
$conexion = $db->conectar();

?>
