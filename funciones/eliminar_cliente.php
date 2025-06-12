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
        window.location.href = "clientes.php";
    </script>';
    exit;
}

require_once "../conexion.php";
$conexion = conexionBD();

// Verificar que el parámetro id_cliente existe
if (!isset($_POST['id_cliente'])) {
    echo json_encode(["success" => false, "message" => "No se proporcionó el ID del cliente"]);
    exit;
}

$id_cliente = $_POST['id_cliente'];
$id_cliente = mysqli_real_escape_string($conexion, $id_cliente);

$consulta = "DELETE FROM clientes WHERE id_cliente = '$id_cliente'";
$resultado = $conexion->query($consulta);

if ($resultado) {
    echo json_encode(["success" => true, "message" => "Cliente eliminado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar cliente: " . $conexion->error]);
}
?>