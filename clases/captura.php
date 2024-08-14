<?php
session_start(); // Iniciar sesión para acceder a los productos del carrito

require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

// Verificar conexión a la base de datos
if (!$con) {
    die(json_encode(['status' => 'error', 'message' => 'No se pudo conectar a la base de datos.']));
}

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

// Verificar los datos recibidos
if (!is_array($datos) || !isset($datos['detalles'])) {
    die(json_encode(['status' => 'error', 'message' => 'Datos inválidos.']));
}

$id_cliente = $_SESSION['user_cliente'];
$sql_cliente = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
$sql_cliente->execute([$id_cliente]);
$row_cliente = $sql_cliente->fetch(PDO::FETCH_ASSOC);

// Verificar si el cliente existe
if (!$row_cliente) {
    die(json_encode(['status' => 'error', 'message' => 'Cliente no encontrado.']));
}

// Extraer datos
$id_transaccion = $datos['detalles']['id'];
$total = $datos['detalles']['purchase_units'][0]['amount']['value'];
$status = $datos['detalles']['status'];
$fecha = $datos['detalles']['update_time'];
$fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
$email = $row_cliente['email'];

// Verificar que los datos esenciales no estén vacíos
if (empty($id_transaccion) || empty($total) || empty($status) || empty($email) || empty($id_cliente)) {
    die(json_encode(['status' => 'error', 'message' => 'Faltan datos esenciales para la transacción.']));
}

try {
    // Iniciar una transacción
    $con->beginTransaction();

    // Insertar datos en la tabla compra
    $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total) VALUES (?, ?, ?, ?, ?, ?)");
    $success = $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total]);

    if ($success) {
        $id_compra = $con->lastInsertId();

        $productos = isset($_SESSION['carrito']['producto']) ? $_SESSION['carrito']['producto'] : null;
        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {
                $sql_prod = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                $sql_prod->execute([$clave]);
                $row_prod = $sql_prod->fetch(PDO::FETCH_ASSOC);

                if ($row_prod) {
                    $precio = $row_prod['precio'];
                    $descuento = $row_prod['descuento'];
                    $precio_desc = $precio - (($precio * $descuento) / 100);

                    $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?, ?, ?, ?, ?)");
                    $sql_insert->execute([$id_compra, $clave, $row_prod['nombre'], $precio_desc, $cantidad]);
                }
            }
        }

        // Commit de la transacción
        $con->commit();
        unset($_SESSION['carrito']);
        echo json_encode(['status' => 'success', 'id' => $id_compra]);
    } else {
        // Rollback de la transacción en caso de error
        $con->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'No se pudo completar la transacción.']);
    }
} catch (PDOException $e) {
    // Rollback de la transacción en caso de excepción
    $con->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
