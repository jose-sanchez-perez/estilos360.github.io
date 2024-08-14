<?php
include 'db_config.php';

$id = $_GET["id"];

$sql = "DELETE FROM productos WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Producto eliminado exitosamente";
} else {
    echo "Error al eliminar el producto: " . $conn->error;
}

$conn->close();
header("Location: productos.php");
exit;
?>
