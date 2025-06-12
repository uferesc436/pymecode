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
    <link rel="stylesheet" href="css/editar_producto.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/actualizar_producto.js"></script>
    <title>Nuevo Producto</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="productos.php">Volver</a>
        </nav>
    </header>

    <?php

    // Obtener datos del producto a editar
    $id_producto = $_POST['id_producto'] ?? null;

    if ($id_producto) {
        $consulta = "SELECT * FROM productos WHERE id_producto = '$id_producto'";
        $result = mysqli_query($conexion, $consulta);

        if ($result && mysqli_num_rows($result) > 0) {
            $producto = mysqli_fetch_assoc($result);
            $id_producto = $producto['id_producto'];
            $nombre_producto = $producto['nombre_producto'];
            $precio_producto = $producto['precio_producto'];
            $stock_producto = $producto['stock_producto'];
        } else {
            echo "<p class='error'>producto no encontrado.</p>";
        }
    } else {
        echo "<p class='error'>ID de producto no proporcionado.</p>";
    }


    ?>

    <div class="tarjeta-editar-producto">
        <h2>Editar datos de <?php echo $nombre_producto; ?></h2>
        <form id="formulario-editar-producto" method="POST">
            <input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto; ?>">
            <div class="campo-formulario">
                <i class="fa-solid fa-tag"></i>
                <input type="text" id="nombre_producto" name="nombre_producto" value="<?php echo $nombre_producto; ?>"
                    placeholder="Nombre del producto" required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-money-bill-1-wave"></i>
                <input type="number" id="precio_producto" name="precio_producto" value="<?php echo $precio_producto; ?>"
                    min="0" step="0.01" placeholder="Precio" required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-boxes-stacked"></i>
                <input type="number" id="stock_producto" name="stock_producto" value="<?php echo $stock_producto; ?>"
                    min="0" placeholder="Stock" required>
            </div>
            <button type="submit" class="boton-enviar"
                onclick="Editar_producto('<?php echo $id_producto; ?>')">Confirmar cambios</button>
        </form>
    </div>

    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>
</body>

</html>