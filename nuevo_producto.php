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
        window.location.href = "productos.php";
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
    <link rel="stylesheet" href="css/nuevo_producto.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/nuevo_producto.js"></script>
    <title>Nuevo Producto</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="productos.php">Volver</a>
        </nav>
    </header>

    <div class="tarjeta-crear-producto">
        <h2>Nuevo Producto</h2>
        <form id="formulario-crear-producto" method="POST">
            <div class="campo-formulario">
                <i class="fa-solid fa-tag"></i>
                <input type="text" id="nombre_producto" name="nombre_producto" placeholder="Nombre del producto"
                    required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-money-bill-1-wave"></i>
                <input type="number" id="precio_producto" name="precio_producto" min="0" step="0.01" placeholder="Precio"
                    required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-boxes-stacked"></i>
                <input type="number" id="stock_producto" name="stock_producto" min="0" placeholder="Stock" required>
            </div>
            <button type="submit" class="boton-enviar">Crear producto</button>
        </form>
    </div>

    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>
</body>

</html>