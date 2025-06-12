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

// Verificar que el parámetro empleado_id existe
if (!isset($_POST['empleado_id'])) {
    echo json_encode(["success" => false, "message" => "No se proporcionó el ID del empleado"]);
    exit;
}

$empleado_id = $_POST['empleado_id'];
$empleado_id = mysqli_real_escape_string($conexion, $empleado_id);

$consulta = "DELETE FROM empleados WHERE id_empleado = '$empleado_id'";
$resultado = $conexion->query($consulta);

if ($resultado) {
    echo json_encode(["success" => true, "message" => "Empleado eliminado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar empleado: " . $conexion->error]);
}
?>