<?php

class Database {
    private $host = "localhost"; // Cambia esto si tu host es diferente
    private $db_name = "estilos_360"; // Reemplaza con el nombre de tu base de datos
    private $username = "root"; // Reemplaza con tu usuario de la base de datos
    private $password = ""; // Reemplaza con tu contraseña de la base de datos
    private $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

?>
