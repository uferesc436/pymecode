<?php
session_start();
require_once "../conexion.php";

// Inicializar la conexión
$conexion = conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario directamente
    $id_empleado = $_POST["id_empleado"];
    $nombre_empleado = $_POST["nombre_empleado"];
    $email_empleado = $_POST["email_empleado"];
    $contraseña_empleado = $_POST["contraseña_empleado"];

    // Verificar si algún campo obligatorio está vacío
    if (empty($id_empleado) || empty($nombre_empleado) || empty($email_empleado) || empty($contraseña_empleado)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
        exit;
    }

    // Verificar si el correo ya existe (excluyendo el correo actual del empleado)
    $verificar_email_empresa = "SELECT email_empresa FROM empresas WHERE email_empresa = '$email_empleado'";
    $resultado_email = $conexion->query($verificar_email_empresa);
    if ($resultado_email->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
        exit;
    }

    $verificar_email_empleado = "SELECT email_empleado FROM empleados WHERE email_empleado = '$email_empleado' AND id_empleado != '$id_empleado'";
    $resultado_email = $conexion->query($verificar_email_empleado);
    if ($resultado_email->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
        exit;
    }

    $verificar_email_cliente = "SELECT email_cliente FROM clientes WHERE email_cliente = '$email_empleado'";
    $resultado_email = $conexion->query($verificar_email_cliente);
    if ($resultado_email->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
        exit;
    }

    // Actualizar los datos del empleado
    $consulta = "UPDATE empleados SET 
                    nombre_empleado = '$nombre_empleado', 
                    email_empleado = '$email_empleado', 
                    contraseña_empleado = '$contraseña_empleado' 
                    WHERE id_empleado = '$id_empleado'";


    if ($conexion->query($consulta) === TRUE) {
        echo json_encode(["success" => true, "message" => "Empleado actualizado correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar datos: " . $conexion->error]);
    }
}

// Cerrar la conexión
$conexion->close();
?>