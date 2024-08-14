<?php 

// Define una constante para el token de seguridad
define("CLIENT_ID", "ARaaYPiXOrQ67iVIZ8YPLhQw2ryfVBJ4-wOLhxhmYGqDKIeG-h9ZrK295q--k0QErj_mddc1EsOpcj7N"); // Asegúrate de que este token sea seguro y complejo
define("CURRENCY", "MXN");
define("KEY_TOKEN", "ABC.wqc-354*");

// Define una constante para la moneda utilizada en el sitio
define("MONEDA", "$"); // Puedes cambiar esto si tu sitio soporta múltiples monedas



$number_cat = 0;
if (isset($_SESSION['carrito']['producto'])) {
    $number_cat = count($_SESSION['carrito']['producto']);
}

?>
