<?php
session_start();
require_once "../conexion.php";

// Inicializar la conexión
$conexion = conexionBD();

// Obtener los datos del formulario directamente
$nombre_producto = $_POST["nombre_producto"];
$precio_producto = $_POST["precio_producto"];
$stock_producto = $_POST["stock_producto"];
$id_empresa_producto = $_SESSION["usuario_id"];

// Verificar si algún campo obligatorio está vacío
if (empty($nombre_producto) || $precio_producto === "" || $stock_producto === "") {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
    exit;
}

if (!is_numeric($precio_producto) || $precio_producto < 0) {
    echo json_encode(["success" => false, "message" => "El precio debe ser un número positivo"]);
    exit;
}

if (!is_numeric($stock_producto) || $stock_producto < 0) {
    echo json_encode(["success" => false, "message" => "El stock debe ser un número positivo"]);
    exit;
}
// Si pasó todas las verificaciones, proceder con la inserción
$consulta = "INSERT INTO productos (nombre_producto, precio_producto, stock_producto, id_empresa_producto) VALUES (
            '" . $nombre_producto . "',
            '" . $precio_producto . "',
            '" . $stock_producto . "',
            '" . $id_empresa_producto . "'
        )";

if ($conexion->query($consulta) === TRUE) {
    echo json_encode(["success" => true, "message" => "Producto registrado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al insertar datos: " . $conexion->error]);
}

// Cerrar la conexión
$conexion->close();
?>