<?php
include 'db_config.php';

$id = $_GET['id'];

$sql = "DELETE FROM empleados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Empleado eliminado exitosamente.";
    header("Location: usuarios.php");
} else {
    echo "Error al eliminar empleado: " . $stmt->error;
}
?>
