<?php
require_once "../conexion.php";

// Inicializar la conexión
$conexion = conexionBD();

// Obtener los datos del formulario directamente
$id_empresa = $_POST["nif_empresa"];
$nombre_empresa = $_POST["nombre_empresa"];
$email_empresa = $_POST["correo_empresa"];
$contraseña_empresa = $_POST["contraseña_empresa"];
$telefono_empresa = $_POST["telefono_empresa"];
$direccion_empresa = $_POST["direccion_empresa"];

// Verificar si algún campo obligatorio está vacío
if (empty($id_empresa) || empty($nombre_empresa) || empty($email_empresa) || 
    empty($contraseña_empresa) || empty($telefono_empresa) || empty($direccion_empresa)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
    exit;
}

// Verificar si el ID de empresa ya existe
$verificar_id = "SELECT id_empresa FROM empresas WHERE id_empresa = '$id_empresa'";
$resultado_id = $conexion->query($verificar_id);
if ($resultado_id->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El NIF de empresa ya está registrado."]);
    exit;
}

// Verificar si el correo ya existe
$verificar_email_empresa = "SELECT email_empresa FROM empresas WHERE email_empresa = '$email_empresa'";
$resultado_email = $conexion->query($verificar_email_empresa);
if ($resultado_email->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

$verificar_email_empleado = "SELECT email_empleado FROM empleados WHERE email_empleado = '$email_empresa'";
$resultado_email = $conexion->query($verificar_email_empleado);
if ($resultado_email->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

$verificar_email_cliente = "SELECT email_cliente FROM clientes WHERE email_cliente = '$email_empresa'";
$resultado_email = $conexion->query($verificar_email_cliente);
if ($resultado_email->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

// Verificar si el teléfono ya existe en la tabla empresas
$verificar_telefono = "SELECT telefono_empresa FROM empresas WHERE telefono_empresa = '$telefono_empresa'";
$resultado_telefono = $conexion->query($verificar_telefono);
if ($resultado_telefono->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El número de teléfono ya está en uso."]);
    exit;
}

// Verificar si el teléfono ya existe en la tabla clientes
$verificar_telefono_cliente = "SELECT telefono_cliente FROM clientes WHERE telefono_cliente = '$telefono_empresa'";
$resultado_telefono = $conexion->query($verificar_telefono_cliente);
if ($resultado_telefono->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El número de teléfono ya está en uso."]);
    exit;
}

// Si pasó todas las verificaciones, proceder con la inserción
$consulta = "INSERT INTO empresas (id_empresa, nombre_empresa, email_empresa, contraseña_empresa, telefono_empresa, direccion_empresa) VALUES (
            '" . $id_empresa . "',
            '" . $nombre_empresa . "',
            '" . $email_empresa . "',
            '" . $contraseña_empresa . "',
            '" . $telefono_empresa . "',
            '" . $direccion_empresa . "'
        )";

if ($conexion->query($consulta) === TRUE) {
    echo json_encode(["success" => true, "message" => "Empresa registrada correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al insertar datos: " . $conexion->error]);
}

// Cerrar la conexión
$conexion->close();
?>