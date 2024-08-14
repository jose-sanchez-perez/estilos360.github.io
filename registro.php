<?php
session_start(); // Iniciar sesión

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();
$errors = [];

if (!empty($_POST)) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $telefono, $usuario, $password])) {
        $errors[] = "Debes llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección del correo es incorrecta";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Error de contraseña";
    }

    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre del usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "El correo $email ya existe";
    }

    if (count($errors) == 0) {
        // Registrar cliente
        $id = registrar([$nombres, $apellidos, $email, $telefono], $con);

        if ($id > 0) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $token = generarToken();
            if (!registrarUsuario([$usuario, $pass_hash, $token, $id], $con)) {
                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }
    }

    if (count($errors) == 0) {
        // Redireccionar a login.php
        header("Location: login.php");
        exit;
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
            <a href="index.php" class="nav-link active">Catálogo</a>
          </li>
        </ul>
        <a href="checkout.php" class="btn btn-primary my-2 my-lg-0">Carrito <span id="num_cart" class="badge bg-secondary"><?php echo isset($_SESSION['carrito']['producto']) ? count($_SESSION['carrito']['producto']) : 0; ?></span></a>
      </div>
    </div>
  </nav>
</header>

<div class="container mt-4">
    <?php mostrarMensajes($errors); ?>
</div>

<main>
    <div class="container mt-4">
        <h2>Datos del cliente</h2>

        <form class="row g-3" action="registro.php" method="post" autocomplete="off">
            <div class="col-md-6">
                <label for="nombres"><span class="text-danger">*</span> Nombre</label>
                <input type="text" name="nombres" id="nombres" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="email"><span class="text-danger">*</span> Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <span id="validaEmail" class="text-danger"></span>
            </div>
            <div class="col-md-6">
                <label for="telefono"><span class="text-danger">*</span> Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="usuario"><span class="text-danger">*</span> Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
                <span id="validaUsuario" class="text-danger"></span>
            </div>
            <div class="col-md-6">
                <label for="password"><span class="text-danger">*</span> Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="repassword"><span class="text-danger">*</span> Repetir Contraseña</label>
                <input type="password" name="repassword" id="repassword" class="form-control" required>
            </div>
            <div class="col-12">
                <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7HUbX39j5nQBOFqIvz8JoJ7+LG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
    let txtUsuario = document.getElementById('usuario');
    txtUsuario.addEventListener("blur", function(){
        existeUsuario(txtUsuario.value);
    }, false);

    let txtEmail = document.getElementById('email');
    txtEmail.addEventListener("blur", function(){
        existeEmail(txtEmail.value);
    }, false);

    function existeUsuario(usuario){
        let url = "clases/clienteAjax.php";
        let formData = new FormData();
        formData.append("action", "existeUsuario");
        formData.append("usuario", usuario);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                document.getElementById('usuario').value = '';
                document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible';
            } else {
                document.getElementById('validaUsuario').innerHTML = '';
            }
        });
    }

    function existeEmail(email){
        let url = "clases/clienteAjax.php";
        let formData = new FormData();
        formData.append("action", "existeEmail");
        formData.append("email", email);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                document.getElementById('email').value = '';
                document.getElementById('validaEmail').innerHTML = 'Email no disponible';
            } else {
                document.getElementById('validaEmail').innerHTML = '';
            }
        });
    }
</script>

</body>
</html>
