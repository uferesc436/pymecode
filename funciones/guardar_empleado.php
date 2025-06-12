<?php
session_start();
require_once "../conexion.php";

// Inicializar la conexión
$conexion = conexionBD();

// Obtener los datos del formulario directamente
$id_empleado = $_POST["id_empleado"];
$nombre_empleado = $_POST["nombre_empleado"];
$email_empleado = $_POST["email_empleado"];
$contraseña_empleado = $_POST["contraseña_empleado"];
$id_empresa_empleado = $_SESSION["usuario_id"];

// Verificar si algún campo obligatorio está vacío
if (empty($id_empleado) || empty($nombre_empleado) || empty($email_empleado) || 
    empty($contraseña_empleado)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
    exit;
}

// Verificar si el ID del empleado ya existe
$verificar_id_empleado = "SELECT id_empleado FROM empleados WHERE id_empleado = '$id_empleado'";
$resultado_id_empleado = $conexion->query($verificar_id_empleado);
if ($resultado_id_empleado->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El DNI del empleado ya está registrado."]);
    exit;
}
$verificar_id_cliente = "SELECT id_cliente FROM clientes WHERE id_cliente = '$id_empleado'";
$resultado_id_cliente = $conexion->query($verificar_id_cliente);
if ($resultado_id_cliente->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El DNI del empleado ya está registrado."]);
    exit;
}

// Verificar si el correo ya existe en la tabla empresas
$verificar_email_empresa = "SELECT email_empresa FROM empresas WHERE email_empresa = '$email_empleado'";
$resultado_email = $conexion->query($verificar_email_empresa);
if ($resultado_email->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

// Verificar si el correo ya existe en la tabla empleados
$verificar_email_empleado = "SELECT email_empleado FROM empleados WHERE email_empleado = '$email_empleado'";
$resultado_email = $conexion->query($verificar_email_empleado);
if ($resultado_email->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

// Verificar si el correo ya existe en la tabla clientes
$verificar_email_cliente = "SELECT email_cliente FROM clientes WHERE email_cliente = '$email_empleado'";
$resultado_email = $conexion->query($verificar_email_cliente);
if ($resultado_email->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

// Si pasó todas las verificaciones, proceder con la inserción
$consulta = "INSERT INTO empleados (id_empleado, nombre_empleado, email_empleado, contraseña_empleado, id_empresa_empleado) VALUES (
            '" . $id_empleado . "',
            '" . $nombre_empleado . "',
            '" . $email_empleado . "',
            '" . $contraseña_empleado . "',
            '" . $id_empresa_empleado . "'
        )";

if ($conexion->query($consulta) === TRUE) {
    echo json_encode(["success" => true, "message" => "Empleado registrado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al insertar datos: " . $conexion->error]);
}

// Cerrar la conexión
$conexion->close();
?>