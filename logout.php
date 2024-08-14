<?php

require 'config/config.php';

session_start(); // Iniciar sesión antes de destruirla
session_destroy(); // Destruir la sesión

header("Location: index.php"); // Redireccionar a la página de inicio
exit(); // Asegurar que el script se detenga después de la redirección

?>