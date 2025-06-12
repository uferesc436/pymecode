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
    <link rel="stylesheet" href="css/nuevo_empleado.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/nuevo_empleado.js"></script>
    <title>Nuevo empleado</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="empleados.php">Volver</a>
        </nav>
    </header>

    <div class="tarjeta-crear-empleado">
        <h2>Crear nuevo empleado</h2>
        <form id="formulario-crear-empleado" onsubmit="Crear_empleado(event)">
            <div class="campo-formulario">
                <i class="fa-solid fa-id-card"></i>
                <input type="text" placeholder="DNI del empleado" name="id_empleado" required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-signature"></i>
                <input type="text" placeholder="Nombre del empleado" name="nombre_empleado" required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" placeholder="Correo del empleado" name="email_empleado" required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-lock"></i>
                <input type="password" placeholder="Contraseña del empleado" name="contraseña_empleado" required>
            </div>
            <button type="submit" class="boton-enviar">Crear empleado</button>
        </form>
    </div>


    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>
</body>

</html>