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

// Incluir la conexión a la base de datos si es necesario
require_once "conexion.php";
$conexion = conexionBD();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/index.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Inicio</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="#" onclick="cerrarSesion(); return false;">Cerrar Sesión</a>        
        </nav>
    </header>

    <?php
    // Mostrar un mensaje de bienvenida dependiendo del rol
    if ($rol == "empresa") {
        echo "<h1 class='bienvenida'>Bienvenido a $nombreUsuario</h1>";
    } elseif ($rol == "empleado") {
        // Obtener el nombre de la empresa del empleado
        $id_empresa_empleado = $_SESSION["usuario_empresa_id"];
        $consulta_empresa = "SELECT nombre_empresa FROM empresas WHERE id_empresa = '$id_empresa_empleado'";
        $resultado_empresa = $conexion->query($consulta_empresa);
        $empresa_empleado = $resultado_empresa->fetch_assoc();
        $nombreEmpresaEmpleado = $empresa_empleado["nombre_empresa"];
        echo "<h1 class='bienvenida'>Bienvenido $nombreUsuario a $nombreEmpresaEmpleado</h1>";
    }
    ?>

    <div class="contenedor">
        <button class="boton_menu" onclick="IrAEmpleados();">
            <div id="div_empleados" class="contenedor_boton_menu">
                <img src="./img/index/EMPLEADOS.png" class="imagen_boton">
                <h2 class="texto_menu">EMPLEADOS</h2>
            </div>
        </button>

        <button class="boton_menu" onclick="IrAProductos();">
            <div id="div_productos" class="contenedor_boton_menu">
                <img src="./img/index/PRODUCTOS.png" class="imagen_boton">
                <h2 class="texto_menu">PRODUCTOS</h2>
            </div>
        </button>

        <button class="boton_menu" onclick="IrAClientes();">
            <div id="div_proveedores" class="contenedor_boton_menu">
                <img src="./img/index/CLIENTES.png" class="imagen_boton">
                <h2 class="texto_menu">CLIENTES</h2>
            </div>
        </button>

        <button class="boton_menu" onclick="IrAFacturas();">
            <div id="div_facturas" class="contenedor_boton_menu">
                <img src="./img/index/FACTURAS.png" class="imagen_boton">
                <h2 class="texto_menu">FACTURAS</h2>
            </div>
        </button>

    </div>

    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>

    <script src="funciones/redirigir_index.js"></script>
    <script>
        function cerrarSesion() {
            if (confirm('¿Está seguro que desea cerrar sesión?')) {
                window.location.href = 'funciones/cerrar_sesion.php';
            }
        }
    </script>
</body>

</html>