<?php
session_start(); // Iniciar sesión

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();
$errors = [];

if (!empty($_POST)) {
    $email = trim($_POST['email']);

    if (esNulo([$email])) {
        $errors[] = "Debes llenar todos los campos";
    } elseif (!esEmail($email)) {
        $errors[] = "La dirección de correo no es válida";
    } else {
        if (emailExiste($email, $con)) {
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes ON usuarios.id_cliente = clientes.id WHERE clientes.email = ? LIMIT 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $user_id = $row['id'];
                $nombres = $row['nombres'];
                $token = solicitaPassword($user_id, $con);
                if ($token !== null) {
                    header("Location: recuperar_cuenta.php?token=$token");
                    exit;
                } else {
                    $errors[] = "Error al generar el token de recuperación";
                }
            } else {
                $errors[] = "Error al buscar el usuario";
            }
        } else {
            $errors[] = "El correo electrónico no está registrado";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
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
        .form-login {
            max-width: 400px;
            margin: auto;
            padding: 15px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong>ESTILOS 360</strong>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="#" class="nav-link active">Catálogo</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">Contacto</a>
          </li>
        </ul>
        <a href="checkout.php" class="btn btn-primary my-2 my-lg-0">Carrito <span id="num_cart" class="badge bg-secondary"><?php echo isset($_SESSION['carrito']['producto']) ? count($_SESSION['carrito']['producto']) : 0; ?></span></a>
      </div>
    </div>
  </nav>
</header>

<div class="container mt-4">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<main class="form-login">
    <h2 class="text-center">Recuperar cuenta</h2>
    <form action="" method="post" autocomplete="off">

        <div class="form-floating mb-3 col-12">
            <label for="email">Correo electrónico</label>
            <input class="form-control" type="email" name="email" id="email" placeholder="Email" required>
        </div>

        <div class="col-12 text-center">
            <button class="btn btn-primary" type="submit">Ingresar</button>
        </div>
        <hr>
        <div class="col-12 text-center">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUbX39j5nQBOFqIvz8JoJ7+LG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
