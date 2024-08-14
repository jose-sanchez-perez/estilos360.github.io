<?php
session_start();
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Este ID de transacción debería ser único. Aquí es simulado, pero en un entorno real, PayPal o BBVA te proporcionarían este ID.
$id_transaccion = uniqid();

// Aquí se manejaría la lógica de procesamiento del pago con PayPal o BBVA
$pago_exitoso = true;

if ($pago_exitoso) {
    try {
        // Obtener datos del usuario
        $email_cliente = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : "cliente@example.com";
        $status = "completado";
        $id_cliente = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; // ID del usuario

        // Calcular el total
        $productos = isset($_SESSION['carrito']['producto']) ? $_SESSION['carrito']['producto'] : array();
        $total = 0;
        foreach ($productos as $id => $cantidad) {
            // Obtener detalles del producto
            $sql = $con->prepare("SELECT precio FROM productos WHERE id=?");
            $sql->execute([$id]);
            $producto = $sql->fetch(PDO::FETCH_ASSOC);
            if ($producto) {
                $total += $cantidad * $producto['precio'];
            }
        }

        // Insertar datos en la tabla `compra`
        $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, total, id_cliente) VALUES (?, NOW(), ?, ?, ?, ?)");
        $sql->execute([$id_transaccion, $status, $email_cliente, $total, $id_cliente]);
        $id_compra = $con->lastInsertId();

        // Insertar datos en la tabla `detalle_compra`
        foreach ($productos as $id => $cantidad) {
            // Obtener detalles del producto
            $sql = $con->prepare("SELECT nombre, precio FROM productos WHERE id=?");
            $sql->execute([$id]);
            $producto = $sql->fetch(PDO::FETCH_ASSOC);

            if ($producto) {
                $nombre = $producto['nombre'];
                $precio = $producto['precio'];
                $subtotal = $cantidad * $precio;

                // Guardar cada producto en `detalle_compra`
                $sql = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?, ?, ?, ?, ?)");
                $sql->execute([$id_compra, $id, $nombre, $precio, $cantidad]);
            }
        }

        // Limpiar el carrito
        unset($_SESSION['carrito']);

        // Redirigir al ticket
        header("Location: ticket.php?id_compra=" . $id_compra);
        exit;
    } catch (Exception $e) {
        echo "Error al procesar la compra: " . $e->getMessage();
    }
} else {
    echo "Error en el proceso de pago.";
}
?>
