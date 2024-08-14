<?php
session_start();

require '../config/config.php';
require '../config/database.php';

$respuesta = array();

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($action == 'actualizar') {
        $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

        if ($id > 0 && $cantidad > 0 && is_numeric($cantidad)) {
            $_SESSION['carrito']['producto'][$id] = $cantidad;

            $db = new Database();
            $con = $db->conectar();
            $sql = $con->prepare("SELECT precio, descuento FROM productos WHERE id=? AND activo=1");
            $sql->execute([$id]);
            $producto = $sql->fetch(PDO::FETCH_ASSOC);

            if ($producto) {
                $precio = $producto['precio'];
                $descuento = $producto['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);
                $subtotal = $cantidad * $precio_desc;

                $respuesta['ok'] = true;
                $respuesta['subtotal'] = number_format($subtotal, 2, '.', ',');
            } else {
                $respuesta['ok'] = false;
                $respuesta['mensaje'] = 'Producto no encontrado';
            }
        } else {
            $respuesta['ok'] = false;
            $respuesta['mensaje'] = 'Datos inválidos';
        }
    } elseif ($action == 'eliminar') {
        if (eliminar($id)) {
            $respuesta['ok'] = true;
        } else {
            $respuesta['ok'] = false;
            $respuesta['mensaje'] = 'Producto no encontrado o no se pudo eliminar';
        }
    } else {
        $respuesta['ok'] = false;
        $respuesta['mensaje'] = 'Acción no permitida';
    }
} else {
    $respuesta['ok'] = false;
    $respuesta['mensaje'] = 'Acción no permitida';
}

echo json_encode($respuesta);

function eliminar($id) {
    if ($id > 0 && isset($_SESSION['carrito']['producto'][$id])) {
        unset($_SESSION['carrito']['producto'][$id]);
        return true;
    } else {
        return false;
    }
}
?>
