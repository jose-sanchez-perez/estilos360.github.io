<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTILOS 360 - Pago con BBVA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        .error-message { color: red; }
    </style>
</head>
<body>

<div class="container mt-4">
    <h4>Pagar con BBVA</h4>
    <form id="payment-form" action="procesar_pago.php" method="POST">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group col-md-6">
                <label for="apellidos">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
        </div>
        <div class="form-group">
            <label for="colonia">Colonia</label>
            <input type="text" class="form-control" id="colonia" name="colonia" required>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="ciudad">Ciudad</label>
                <input type="text" class="form-control" id="ciudad" name="ciudad" required>
            </div>
            <div class="form-group col-md-4">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" required>
            </div>
            <div class="form-group col-md-4">
                <label for="codigo_postal">Código Postal</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" pattern="\d{5}" title="Debe ingresar 5 números." required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="celular">Celular</label>
                <input type="tel" class="form-control" id="celular" name="celular" pattern="\d{10}" title="Debe ingresar 10 números." required>
            </div>
            <div class="form-group col-md-6">
                <label for="correo">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="numero_tarjeta">Número de Tarjeta</label>
                <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" pattern="\d{18}" title="Debe ingresar 18 números." required>
            </div>
            <div class="form-group col-md-4">
                <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                <input type="month" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
            </div>
            <div class="form-group col-md-4">
                <label for="csc">CSC</label>
                <input type="text" class="form-control" id="csc" name="csc" pattern="\d{3}" title="Debe ingresar 3 números." required>
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox" id="enviar_direccion" name="enviar_direccion" checked>
            <label for="enviar_direccion">Enviar a la dirección de su tarjeta</label>
        </div>
        <div class="form-group">
            <input type="checkbox" id="confirmacion" name="confirmacion" required>
            <label for="confirmacion">Confirmo que soy mayor de edad y acepto el <a href="#">Aviso de Privacidad</a></label>
        </div>
        <button type="submit" class="btn btn-primary">Pagar</button>
    </form>
</div>

<script>
    document.getElementById('payment-form').addEventListener('submit', function(event) {
        const fechaVencimiento = new Date(document.getElementById('fecha_vencimiento').value + '-01');
        const hoy = new Date();
        
        // Ajustar la fecha de hoy a solo año y mes
        hoy.setDate(1);
        hoy.setHours(0, 0, 0, 0);

        if (fechaVencimiento < hoy) {
            event.preventDefault();
            alert('La fecha de vencimiento no puede ser una fecha pasada.');
        }
    });
</script>

</body>
</html>
