<?php
session_start();

require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Obtener productos del carrito de la sesión
$productos = isset($_SESSION['carrito']['producto']) ? $_SESSION['carrito']['producto'] : null;

$lista_carrito = array();
$total = 0;

if ($productos != null) {
    foreach ($productos as $id => $cantidad) {
        // Obtener detalles del producto desde la base de datos
        $sql = $con->prepare("SELECT id, nombre, descuento, precio FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $precio = $producto['precio'];
            $descuento = $producto['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $subtotal = $cantidad * $precio_desc;

            // Actualizar la cantidad y subtotal en el array del producto
            $producto['cantidad'] = $cantidad;
            $producto['subtotal'] = $subtotal;

            // Agregar producto actualizado a la lista del carrito
            $lista_carrito[] = $producto;

            // Calcular total general
            $total += $subtotal;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTILOS 360</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .card-custom {
            width: 14rem;
        }
        .card-custom img {
            height: 150px;
            object-fit: contain;
        }
        .discount-price {
            color: green;
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container mt-4">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lista_carrito)): ?>
                        <tr><td colspan="5" class="text-center"><b>Lista vacía</b></td></tr>
                    <?php else: ?>
                        <?php foreach ($lista_carrito as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo MONEDA . number_format($producto['precio'] - (($producto['precio'] * $producto['descuento']) / 100), 2, '.', ','); ?></td>
                                <td>
                                    <input type="number" min="1" max="10" step="1" value="<?php echo $producto['cantidad']; ?>" size="5" id="cantidad_<?php echo $producto['id']; ?>" onchange="actualizaCantidad(this.value, <?php echo $producto['id']; ?>)">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $producto['id']; ?>"><?php echo MONEDA . number_format($producto['subtotal'], 2, '.', ','); ?></div>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm eliminar" data-id="<?php echo $producto['id']; ?>" onclick="showEliminarModal(<?php echo $producto['id']; ?>)">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">
                                <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($lista_carrito != null) {?>
        <div class="row">
            <div class="col-md-5 offset-md-7 d-grid gap-2">
                <?php if(isset($_SESSION['user_id'])){ ?>
                <a href="pago.php" class="btn btn-primary btn-lg">Realizar pago</a>
                <?php } else {?>
                    <a href="login.php?pago" class="btn btn-primary btn-lg">Realizar pago</a>
                    <?php }?>
            </div>
        </div><?php } ?>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que quieres eliminar este producto del carrito?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btn-eliminar" onclick="eliminar()">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUbX39j5nQBOFqIvz8JoJ7+LG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
    function showEliminarModal(id) {
        $('#eliminaModal').modal('show');
        document.getElementById('btn-eliminar').setAttribute('data-id', id);
    }

    function actualizaCantidad(cantidad, id) {
        let url = 'clases/actualizar_carrito.php';
        let formData = new FormData();
        formData.append('action', 'actualizar');
        formData.append('id', id);
        formData.append('cantidad', cantidad);

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if (data.ok) {
                // Actualizar el subtotal del producto modificado
                let divSubtotal = document.getElementById('subtotal_' + id);
                divSubtotal.textContent = '<?php echo MONEDA; ?>' + data.subtotal;

                // Recalcular el total general
                actualizarTotal();
            } else {
                alert('Error al actualizar la cantidad del producto: ' + data.mensaje);
            }
        }).catch(error => {
            console.error('Error en la solicitud:', error);
        });
    }

    function actualizarTotal() {
        let total = 0;
        let subtotales = document.querySelectorAll('[id^=subtotal_]');

        subtotales.forEach(subtotal => {
            total += parseFloat(subtotal.textContent.replace(/[^\d.-]/g, ''));
        });

        // Mostrar el total actualizado en la página
        let divTotal = document.getElementById('total');
        divTotal.textContent = '<?php echo MONEDA; ?>' + total.toFixed(2);
    }

    function eliminar() {
        let botonElimina = document.getElementById('btn-eliminar');
        let id = botonElimina.getAttribute('data-id');

        let url = 'clases/actualizar_carrito.php';
        let formData = new FormData();
        formData.append('action', 'eliminar');
        formData.append('id', id);

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if (data.ok) {
                location.reload();
            }
        }).catch(error => {
            console.error('Error en la solicitud:', error);
        });
    }
</script>

</body>
</html>
