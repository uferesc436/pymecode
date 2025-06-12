<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["rol"])) {
    // Si no hay sesión activa, redirigir al login
    header("Location: login.php");
    exit;
}

// Obtener información del usuario de la sesión
$rol = $_SESSION["rol"];
$nombreUsuario = $_SESSION["usuario_nombre"];
$idUsuario = $_SESSION["usuario_id"];

if ($rol == "empleado") {
    echo '<script>
        alert("No tienes permisos para acceder a esta página.");
        window.location.href = "empleados.php";
    </script>';
    exit;
}

require_once "conexion.php";
$conexion = conexionBD();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/editar_empleado.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/actualizar_empleado.js"></script>
    <title>Editar empleado</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="empleados.php">Volver</a>
        </nav>
    </header>

    <?php

    // Obtener datos del empleado a editar
    $id_empleado = $_POST['empleado_id'] ?? null;

    if ($id_empleado) {
        $consulta = "SELECT * FROM empleados WHERE id_empleado = '$id_empleado'";
        $result = mysqli_query($conexion, $consulta);

        if ($result && mysqli_num_rows($result) > 0) {
            $empleado = mysqli_fetch_assoc($result);
            $id_empleado = $empleado['id_empleado'];
            $nombre_empleado = $empleado['nombre_empleado'];
            $email_empleado = $empleado['email_empleado'];
            $contraseña_empleado = $empleado['contraseña_empleado'];
        } else {
            echo "<p class='error'>Empleado no encontrado.</p>";
        }
    } else {
        echo "<p class='error'>ID de empleado no proporcionado.</p>";
    }


    ?>

    <div class="tarjeta-editar-empleado">
        <h2>Editar datos de <?php echo $nombre_empleado; ?></h2>
        <form id="formulario-editar-empleado">
            <input type="hidden" name="id_empleado" value="<?php echo $id_empleado; ?>">
                <div class="campo-formulario">
                    <i class="fa-solid fa-signature"></i>
                    <input type="text" placeholder="Nombre de la empresa" name="nombre_empleado"
                        value="<?php echo $nombre_empleado; ?>" required>
                </div>
                <div class="campo-formulario">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" placeholder="Correo de la empresa" name="email_empleado"
                        value="<?php echo $email_empleado; ?>" required>
                </div>
                <div class="campo-formulario">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="Contraseña de la empresa" name="contraseña_empleado"
                        value="<?php echo $contraseña_empleado; ?>" required>
                </div>
            <button type="button" class="boton-enviar"
                onclick="Editar_empleado('<?php echo $id_empleado; ?>')">Confirmar cambios</button>
        </form>
    </div>

    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>
</body>

</html>