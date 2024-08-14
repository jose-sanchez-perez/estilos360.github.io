<?php

function esNulo($campos) {
    foreach ($campos as $campo) {
        if (trim($campo) == '') {
            return true;
        }
    }
    return false;
}

function login($usuario, $password, $con) {
    // Validar y sanitizar las entradas del usuario
    $usuario = trim($usuario);
    $password = trim($password);

    try {
        // Consulta para buscar al usuario en la tabla empleados con la columna 'contraseña'
        $sql = "SELECT id, contraseña FROM empleados WHERE usuario = :usuario LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Verificar la contraseña ingresada contra la contraseña almacenada
            if (password_verify($password, $resultado['contraseña'])) {
                // Iniciar la sesión y redirigir al usuario al dashboard
                session_start();
                $_SESSION['usuario_id'] = $resultado['id'];
                header("Location: categorias.php");
                exit;
            } else {
                return "Credenciales incorrectas.";
            }
        } else {
            return "Credenciales incorrectas.";
        }
    } catch (PDOException $e) {
        return "Error al realizar la consulta: " . $e->getMessage();
    }
}


function mostrarMensajes($errors) {
    if (count($errors) > 0) {
        echo '<div class="alert alert-danger">';
        foreach ($errors as $error) {
            echo "<p>" . htmlspecialchars($error) . "</p>";
        }
        echo '</div>';
    }
}
?>
