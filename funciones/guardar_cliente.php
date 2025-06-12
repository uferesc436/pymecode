<?php
session_start();
require_once "../conexion.php";

// Inicializar la conexión
$conexion = conexionBD();

// Obtener los datos del formulario
$id_cliente = $_POST["id_cliente"];
$nombre_cliente = $_POST["nombre_cliente"];
$email_cliente = $_POST["email_cliente"];
$telefono_cliente = $_POST["telefono_cliente"];
$id_empresa_cliente = $_SESSION["usuario_id"];

// Verificar si algún campo obligatorio está vacío
if (empty($id_cliente) || empty($nombre_cliente) || empty($email_cliente) || empty($telefono_cliente)) {
    echo json_encode(["success" => false, "message" => "Por favor, completa todos los campos obligatorios."]);
    exit;
}

// Verificar si el ID del cliente ya existe en la tabla empleados
$verificar_id_empleado = "SELECT id_empleado FROM empleados WHERE id_empleado = '$id_cliente'";
$resultado_id_empleado = $conexion->query($verificar_id_empleado);
if ($resultado_id_empleado->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El DNI ya está registrado como empleado."]);
    exit;
}

// Verificar si el ID del cliente ya existe en la tabla clientes
$verificar_id_cliente = "SELECT id_cliente FROM clientes WHERE id_cliente = '$id_cliente'";
$resultado_id_cliente = $conexion->query($verificar_id_cliente);
if ($resultado_id_cliente->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El DNI del cliente ya está registrado."]);
    exit;
}

// Verificar si el correo ya existe en la tabla empresas
$verificar_email_empresa = "SELECT email_empresa FROM empresas WHERE email_empresa = '$email_cliente'";
$resultado_email_empresa = $conexion->query($verificar_email_empresa);
if ($resultado_email_empresa->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

// Verificar si el correo ya existe en la tabla empleados
$verificar_email_empleado = "SELECT email_empleado FROM empleados WHERE email_empleado = '$email_cliente'";
$resultado_email_empleado = $conexion->query($verificar_email_empleado);
if ($resultado_email_empleado->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

// Verificar si el correo ya existe en la tabla clientes
$verificar_email_cliente = "SELECT email_cliente FROM clientes WHERE email_cliente = '$email_cliente'";
$resultado_email_cliente = $conexion->query($verificar_email_cliente);
if ($resultado_email_cliente->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está en uso."]);
    exit;
}

// Verificar si el teléfono ya existe en la tabla empresas
$verificar_telefono_empresa = "SELECT telefono_empresa FROM empresas WHERE telefono_empresa = '$telefono_cliente'";
$resultado_telefono_empresa = $conexion->query($verificar_telefono_empresa);
if ($resultado_telefono_empresa->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El teléfono ya está en uso."]);
    exit;
}

// Verificar si el teléfono ya existe en la tabla clientes
$verificar_telefono_cliente = "SELECT telefono_cliente FROM clientes WHERE telefono_cliente = '$telefono_cliente'";
$resultado_telefono_cliente = $conexion->query($verificar_telefono_cliente);
if ($resultado_telefono_cliente->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El teléfono ya está en uso."]);
    exit;
}

// Si pasó todas las verificaciones, proceder con la inserción
$consulta = "INSERT INTO clientes (id_cliente, nombre_cliente, email_cliente, telefono_cliente, id_empresa_cliente) 
            VALUES ('$id_cliente', 
                    '$nombre_cliente', 
                    '$email_cliente', 
                    '$telefono_cliente',
                    '$id_empresa_cliente')";

if ($conexion->query($consulta) === TRUE) {
    echo json_encode(["success" => true, "message" => "Cliente registrado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al insertar datos: " . $conexion->error]);
}

// Cerrar la conexión
$conexion->close();
?>