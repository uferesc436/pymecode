<?php
session_start();
require_once "../conexion.php";

// Inicializar la conexión
$conexion = conexionBD();

// Obtener los datos del formulario directamente
$email_usuario = $_POST["correo_usuario"];
$contraseña_usuario = $_POST["contraseña_usuario"];

// Verificar si algún campo obligatorio está vacío
if (empty($email_usuario) || empty($contraseña_usuario)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
    exit;
}

// Intentar autenticar como empresa
$verificar_empresa = "SELECT * FROM empresas WHERE email_empresa = '$email_usuario' AND contraseña_empresa = '$contraseña_usuario'";
$resultado_empresa = $conexion->query($verificar_empresa);

// Intentar autenticar como empleado
$verificar_empleado = "SELECT * FROM empleados WHERE email_empleado = '$email_usuario' AND contraseña_empleado = '$contraseña_usuario'";
$resultado_empleado = $conexion->query($verificar_empleado);

// Verificar si se autenticó correctamente
if ($resultado_empresa->num_rows > 0) {
    // Usuario es una empresa
    $empresa = $resultado_empresa->fetch_assoc();

    // Guardar información en la sesión
    $_SESSION["rol"] = "empresa";
    $_SESSION["usuario_id"] = $empresa["id_empresa"];
    $_SESSION["usuario_nombre"] = $empresa["nombre_empresa"];
    $_SESSION["usuario_email"] = $empresa["email_empresa"];

    echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso como empresa"]);
    exit;
} elseif ($resultado_empleado->num_rows > 0) {
    // Usuario es un empleado
    $empleado = $resultado_empleado->fetch_assoc();

    // Guardar información en la sesión
    $_SESSION["rol"] = "empleado";
    $_SESSION["usuario_id"] = $empleado["id_empleado"];
    $_SESSION["usuario_nombre"] = $empleado["nombre_empleado"];
    $_SESSION["usuario_email"] = $empleado["email_empleado"];
    $_SESSION["usuario_empresa_id"] = $empleado["id_empresa_empleado"];

    echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso como empleado"]);
    exit;
} else {
    // Verificar si el email existe para dar un mensaje más específico
    $verificar_email_empresa = "SELECT email_empresa FROM empresas WHERE email_empresa = '$email_usuario'";
    $verificar_email_empleado = "SELECT email_empleado FROM empleados WHERE email_empleado = '$email_usuario'";

    $resultado_email_empresa = $conexion->query($verificar_email_empresa);
    $resultado_email_empleado = $conexion->query($verificar_email_empleado);

    if ($resultado_email_empresa->num_rows > 0 || $resultado_email_empleado->num_rows > 0) {
        // El email existe pero la contraseña es incorrecta
        echo json_encode(["success" => false, "message" => "La contraseña es incorrecta"]);
    } else {
        // El email no existe
        echo json_encode(["success" => false, "message" => "El correo electrónico no está registrado"]);
    }
}

// Cerrar la conexión
$conexion->close();
?>