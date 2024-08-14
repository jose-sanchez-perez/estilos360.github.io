<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Cargar el SDK de PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=ARaaYPiXOrQ67iVIZ8YPLhQw2ryfVBJ4-wOLhxhmYGqDKIeG-h9ZrK295q--k0QErj_mddc1EsOpcj7N&currency=MXN&locale=es_MX"></script>
</head>
<body>
    <!-- Contenedor del botón de PayPal -->
    <div id="paypal-button-container"></div>

    <script>
        // Renderizar el botón de PayPal
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            // Crear la orden de pago
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '100.00' // El valor debe ser una cadena
                        }
                    }]
                });
            },
            // Manejar la cancelación del pago
            onCancel: function(data) {
                alert("Pago cancelado");
                console.log(data);
            },
            // Manejar la aprobación del pago
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transacción completada por ' + details.payer.name.given_name);
                    window.location.href="completado.html"
                });
            }
        }).render('#paypal-button-container');
    </script>
    
</body>
</html>
