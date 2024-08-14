<?php
include 'db_config.php';

$id = $_GET["id"];

$sql = "DELETE FROM categorias WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Categoría eliminada exitosamente";
} else {
    echo "Error al eliminar la categoría: " . $conn->error;
}

$conn->close();
header("Location: categorias.php");
exit;
?>
