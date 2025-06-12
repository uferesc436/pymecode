<?php
session_start();
require_once "../conexion.php";

$conexion = conexionBD();

if (!isset($_SESSION["rol"])) {
    echo json_encode(["success" => false, "message" => "No tienes permisos."]);
    exit;
}

if ($_SESSION["rol"] == "empleado") {
    $id_empresa_empleado = $_SESSION["usuario_empresa_id"];
} else {
    $id_empresa_empleado = $_SESSION["usuario_id"];
}

// Obtener datos enviados por POST
$id_cliente = $_POST['id_cliente'] ?? null;
$detalles = isset($_POST['detalles']) ? json_decode($_POST['detalles'], true) : [];

if (!$id_cliente || empty($detalles)) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
    exit;
}

// Calcular el total
$total_factura = 0;
foreach ($detalles as $detalle) {
    $precio = (float)$detalle['precioPorUnidad'];
    $cantidad = (int)$detalle['cantidad'];
    $total_factura += $precio * $cantidad;
}

// Insertar la factura
$consulta_factura = "INSERT INTO facturas (fecha_factura, total_factura, id_cliente_factura) 
                     VALUES (NOW(), '$total_factura', '$id_cliente')";
mysqli_query($conexion, $consulta_factura);

// Obtener el ID generado
$id_factura = mysqli_insert_id($conexion);

// Insertar detalles
foreach ($detalles as $detalle) {
    $id_producto = $detalle['productoId'];
    $precio = $detalle['precioPorUnidad'];
    $cantidad = $detalle['cantidad'];

    // Insertar en detalles_factura
    $consulta_detalle = "INSERT INTO detalles_factura (cantidad_detalle, precio_por_unidad_detalle, id_factura_detalle, id_producto_detalle)
                         VALUES ('$cantidad', '$precio', '$id_factura', '$id_producto')";
    mysqli_query($conexion, $consulta_detalle);

    // Actualizar stock del producto
    $consulta_stock = "UPDATE productos SET stock_producto = stock_producto - $cantidad WHERE id_producto = '$id_producto'";
    mysqli_query($conexion, $consulta_stock);
}

echo json_encode(["success" => true, "message" => "Factura creada correctamente"]);
$conexion->close();
?>
