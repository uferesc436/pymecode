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
        window.location.href = "productos.php";
    </script>';
    exit;
}

require_once "../conexion.php";
$conexion = conexionBD();

// Verificar que el parámetro id_producto existe
if (!isset($_POST['id_producto'])) {
    echo json_encode(["success" => false, "message" => "No se proporcionó el ID del producto"]);
    exit;
}

$id_producto = $_POST['id_producto'];
$id_producto = mysqli_real_escape_string($conexion, $id_producto);

$consulta = "DELETE FROM productos WHERE id_producto = '$id_producto'";
$resultado = $conexion->query($consulta);

if ($resultado) {
    echo json_encode(["success" => true, "message" => "Producto eliminado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar producto: " . $conexion->error]);
}
?>