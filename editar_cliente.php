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
        window.location.href = "clientes.php";
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
    <link rel="stylesheet" href="css/editar_cliente.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/actualizar_cliente.js"></script>
    <title>Editar cliente</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="clientes.php">Volver</a>
        </nav>
    </header>

    <?php

    // Obtener datos del cliente a editar
    $id_cliente = $_POST['id_cliente'] ?? null;

    if ($id_cliente) {
        $consulta = "SELECT * FROM clientes WHERE id_cliente = '$id_cliente'";
        $result = mysqli_query($conexion, $consulta);

        if ($result && mysqli_num_rows($result) > 0) {
            $cliente = mysqli_fetch_assoc($result);
            $id_cliente = $cliente['id_cliente'];
            $nombre_cliente = $cliente['nombre_cliente'];
            $email_cliente = $cliente['email_cliente'];
            $telefono_cliente = $cliente['telefono_cliente'];
        } else {
            echo "<p class='error'>Cliente no encontrado.</p>";
        }
    } else {
        echo "<p class='error'>ID de cliente no proporcionado.</p>";
    }

    ?>

    <div class="tarjeta-editar-cliente">
        <h2>Editar datos de <?php echo $nombre_cliente; ?></h2>
        <form id="formulario-editar-cliente">
            <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
            <div class="campo-formulario">
                <i class="fa-solid fa-signature"></i>
                <input type="text" placeholder="Nombre del cliente" name="nombre_cliente"
                    value="<?php echo $nombre_cliente; ?>" required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" placeholder="Correo del cliente" name="email_cliente"
                    value="<?php echo $email_cliente; ?>" required>
            </div>
            <div class="campo-formulario">
                <i class="fa-solid fa-phone"></i>
                <input type="text" placeholder="Telefono del cliente" name="telefono_cliente"
                    value="<?php echo $telefono_cliente; ?>" required>
            </div>
            <button type="button" class="boton-enviar"
                onclick="Editar_cliente('<?php echo $id_cliente; ?>')">Confirmar cambios</button>
        </form>
    </div>

    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>
</body>

</html>