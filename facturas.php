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
    $id_empresa_empleado = $_SESSION["usuario_empresa_id"];
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
    <link rel="stylesheet" href="css/facturas.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/eliminar_factura.js"></script>
    <title>Facturas</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="index.php">Volver</a>
        </nav>
    </header>

    <button type="submit" class="boton_nueva_factura" onclick="window.location.href='nueva_factura.php'">Nueva
        factura</button>

    <!-- Filtros -->
    <div class="container_filtro">
        <form id="form-filtros" class="form-filtros">
            <table class="tabla_listas_filtro">
                <thead>
                    <tr>
                        <th class="th_filtro" colspan="3">FILTRAR FACTURAS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td_filtro">
                            <div class="campo-formulario">
                                <i class="fa-solid fa-calendar-days"></i>
                                <input type="date" placeholder="Fecha de la factura" name="fecha_factura">
                            </div>
                        </td>
                        <td class="td_filtro">
                            <div class="campo-formulario">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <input type="number" placeholder="Total de la factura" name="total_factura" min="0" step="0.01">
                            </div>
                        </td>
                        <td class="td_filtro">
                            <div class="campo-formulario">
                                <i class="fa-solid fa-id-card"></i>
                                <input type="text" placeholder="DNI del cliente" name="id_cliente_factura">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

    </div>

    <!-- Contenedor donde se cargarán los resultados -->
    <div id="resultados" class="container_facturas">
        <!-- Resultados de facturas se cargarán aquí con AJAX -->
    </div>

    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>

    <script>
        $(document).ready(function () {
            console.log("Script cargado correctamente");

            function filtrarFacturas() {
                console.log("Iniciando filtrado...");
                console.log("Datos a enviar:", $("#form-filtros").serialize());

                $.ajax({
                    type: "POST",
                    url: "funciones/filtrar_facturas.php",
                    data: $("#form-filtros").serialize(),
                    success: function (response) {
                        console.log(response);
                        $("#resultados").html(response);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la solicitud: " + error);
                        $("#resultados").html("<p>Error al cargar los datos: " + error + "</p>");
                    }
                });
            }

            // Cargar facturas al inicio
            filtrarFacturas();

            // Usar evento para filtrar mientras se escribe
            $("#form-filtros input").on("input", function () {
                console.log("Input detectado en: " + $(this).attr("name"));
                filtrarFacturas();
            });
        });
    </script>
</body>

</html>