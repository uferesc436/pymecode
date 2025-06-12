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
        alert("No tienes permisos para hacer esto.");
        window.location.href = "empleados.php";
    </script>';
    exit;
}

require_once "../conexion.php";
$conexion = conexionBD();

// Verificar que el parámetro id_factura existe
if (!isset($_POST['id_factura'])) {
    echo json_encode(["success" => false, "message" => "No se proporcionó el ID de la factura"]);
    exit;
}

$id_factura = $_POST['id_factura'];
$id_factura = mysqli_real_escape_string($conexion, $id_factura);

// Recuperar los detalles de la factura para actualizar el stock
$sql_detalles = "SELECT id_producto_detalle, cantidad_detalle FROM detalles_factura WHERE id_factura_detalle = '$id_factura'";
$result_detalles = $conexion->query($sql_detalles);

if ($result_detalles) {
    while ($detalle = $result_detalles->fetch_assoc()) {
        $id_producto = $detalle['id_producto_detalle'];
        $cantidad = $detalle['cantidad_detalle'];
        // Actualizar el stock sumando la cantidad eliminada
        $conexion->query("UPDATE productos SET stock_producto = stock_producto + $cantidad WHERE id_producto = '$id_producto'");
    }
}

$consulta = "DELETE FROM facturas WHERE id_factura = '$id_factura'";
$resultado = $conexion->query($consulta);

if ($resultado) {
    echo json_encode(["success" => true, "message" => "Factura eliminado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar factura: " . $conexion->error]);
}
?>