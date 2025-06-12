<?php
session_start();
require_once "../conexion.php";

// Inicializar la conexión
$conexion = conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario directamente
    $id_cliente = $_POST["id_cliente"];
    $nombre_cliente = $_POST["nombre_cliente"];
    $email_cliente = $_POST["email_cliente"];
    $telefono_cliente = $_POST["telefono_cliente"];

    // Verificar si algún campo obligatorio está vacío
    if (empty($id_cliente) || empty($nombre_cliente) || empty($email_cliente) || empty($telefono_cliente)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
        exit;
    }

    // Verificar si el correo ya existe (excluyendo el correo actual del cliente)
    $verificar_email_empresa = "SELECT email_empresa FROM empresas WHERE email_empresa = '$email_cliente'";
    $resultado_email = $conexion->query($verificar_email_empresa);
    if ($resultado_email->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
        exit;
    }

    $verificar_email_empleado = "SELECT email_empleado FROM empleados WHERE email_empleado = '$email_cliente'";
    $resultado_email = $conexion->query($verificar_email_empleado);
    if ($resultado_email->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
        exit;
    }

    $verificar_email_cliente = "SELECT email_cliente FROM clientes WHERE email_cliente = '$email_cliente' AND id_cliente != '$id_cliente'";
    $resultado_email = $conexion->query($verificar_email_cliente);
    if ($resultado_email->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
        exit;
    }


    $consulta = "UPDATE clientes SET 
                        nombre_cliente = '$nombre_cliente',
                        email_cliente = '$email_cliente',
                        telefono_cliente = '$telefono_cliente'
                        WHERE id_cliente = '$id_cliente'";


    if ($conexion->query($consulta) === TRUE) {
        echo json_encode(["success" => true, "message" => "Cliente actualizado correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar datos: " . $conexion->error]);
    }
}

// Cerrar la conexión
$conexion->close();
?>