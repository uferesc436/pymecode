<?php
session_start();
require_once "../conexion.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["rol"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION["rol"] == "empleado") {
    $id_empresa_empleado = $_SESSION["usuario_empresa_id"];
} else {
    $id_empresa_empleado = $_SESSION["usuario_id"];
}

// Verificar que se haya proporcionado el ID de la factura
if (!isset($_POST['id_factura'])) {
    echo "Error: No se proporcionó el ID de la factura";
    exit;
}

$id_factura = $_POST['id_factura'];
$conexion = conexionBD();

// Obtener datos de la factura
$consulta_factura = "SELECT f.*, c.nombre_cliente, c.telefono_cliente, c.email_cliente,c.id_cliente
                     FROM facturas f 
                     INNER JOIN clientes c ON f.id_cliente_factura = c.id_cliente 
                     WHERE f.id_factura = '" . mysqli_real_escape_string($conexion, $id_factura) . "'";

$resultado_factura = mysqli_query($conexion, $consulta_factura);

if (!$resultado_factura || mysqli_num_rows($resultado_factura) == 0) {
    echo "Error: Factura no encontrada";
    exit;
}

$factura = mysqli_fetch_assoc($resultado_factura);

// Obtener detalles de la factura
$consulta_detalles = "SELECT df.*, p.nombre_producto 
                      FROM detalles_factura df 
                      INNER JOIN productos p ON df.id_producto_detalle = p.id_producto 
                      WHERE df.id_factura_detalle = '" . mysqli_real_escape_string($conexion, $id_factura) . "'";

$resultado_detalles = mysqli_query($conexion, $consulta_detalles);

/* Obtener detalles de la empresa que genera la factura */
$consulta_empresa = "SELECT * FROM empresas WHERE id_empresa = '" . mysqli_real_escape_string($conexion, $id_empresa_empleado) . "'";
$resultado_empresa = mysqli_query($conexion, $consulta_empresa);
if (!$resultado_empresa || mysqli_num_rows($resultado_empresa) == 0) {
    echo "Error: Empresa no encontrada";
    exit;
}
$empresa = mysqli_fetch_assoc($resultado_empresa);

// Verificar si se encontraron detalles
if (!$resultado_detalles) {
    echo "Error en la consulta de detalles: " . mysqli_error($conexion);
    exit;
}

// Verificar si existe la librería TCPDF
if (!file_exists('../vendor/tecnickcom/tcpdf/tcpdf.php')) {
    echo "Error: La librería TCPDF no está instalada. Instálala con Composer: composer require tecnickcom/tcpdf";
    exit;
}

// Incluir la librería TCPDF
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

try {
    // Crear nuevo PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Configurar información del documento
    $pdf->SetCreator('PymeCode');
    $pdf->SetAuthor('PymeCode');
    $pdf->SetTitle('Factura #' . $id_factura);
    $pdf->SetSubject('Factura');

    // Configurar márgenes
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);

    // Añadir página
    $pdf->AddPage();

    // Configurar fuente
    $pdf->SetFont('helvetica', '', 10);

    // Encabezado de la empresa
    $html_header = '
    <table style="width:100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td>
                <h1 style="font-size:24pt; color:#003366; margin:0 0 5px 0; font-weight:bold;">PymeCode</h1>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Nombre:</strong> ' . $empresa['nombre_empresa'] . '</p>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Email:</strong> ' . $empresa['email_empresa'] . '</p>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Teléfono:</strong> ' . $empresa['telefono_empresa'] . '</p>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Dirección:</strong> ' . htmlspecialchars($empresa['direccion_empresa']) . '</p>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Fecha: ' . date('d/m/Y', strtotime($factura['fecha_factura'])) . '</strong></p>
            </td>
            <td>
                <h1 style="font-size:24pt; color:#003366; margin:0 0 5px 0; font-weight:bold;">Datos del cliente</h1>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Nombre:</strong> ' . htmlspecialchars($factura['nombre_cliente']) . '</p>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>DNI:</strong> ' . htmlspecialchars($factura['id_cliente']) . '</p>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Email:</strong> ' . htmlspecialchars($factura['email_cliente']) . '</p>
                <p style="font-family: Helvetica, Arial, sans-serif; font-size:9pt; color:#333; margin:2px 0;"><strong>Teléfono:</strong> ' . htmlspecialchars($factura['telefono_cliente']) . '</p>
            </td>
        </tr>
    </table>
    <br><br><br><br>';

    $pdf->writeHTML($html_header, true, false, true, false, '');

    // Tabla de productos
    $html_productos = '
    <table border="0" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse; margin-bottom: 15px;">
        <thead>
            <tr style="background-color: #cce5ff; color: #004085; font-weight: bold; font-size: 10pt;">
                <th style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:1px solid #004085; border-right:none; padding: 6px 8px; text-align: left; vertical-align: middle;">PRODUCTO</th>
                <th style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:none; border-right:none; padding: 6px 8px; text-align: left; vertical-align: middle;">CANTIDAD</th>
                <th style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:none; border-right:none; padding: 6px 8px; text-align: left; vertical-align: middle;">PRECIO UNIDAD</th>
                <th style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:none; border-right:1px solid #004085; padding: 6px 8px; text-align: left; vertical-align: middle;">TOTAL</th>
            </tr>
        </thead>
        <tbody>';

    $subtotal = 0;
    $hay_productos = false;

    while ($detalle = mysqli_fetch_assoc($resultado_detalles)) {
        $hay_productos = true;
        $precio_con_iva = $detalle['precio_por_unidad_detalle'];
        $total_linea_con_iva = $detalle['cantidad_detalle'] * $precio_con_iva;
        $subtotal += $total_linea_con_iva;

        $html_productos .= '
        <tr>
            <td style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:1px solid #004085; border-right:none; padding:6px 8px;">' . htmlspecialchars($detalle['nombre_producto']) . '</td>
            <td style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:none; border-right:none; padding:6px 8px;">' . htmlspecialchars($detalle['cantidad_detalle']) . '</td>
            <td style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:none; border-right:none; padding:6px 8px;">' . number_format($precio_con_iva, 2) . '€</td>
            <td style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:none; border-right:1px solid #004085; padding:6px 8px;">' . number_format($total_linea_con_iva, 2) . '€</td>
        </tr>';
    }

    if (!$hay_productos) {
        $html_productos .= '
            <tr>
                <td colspan="4" style="border: 1px solid #004085; padding: 6px 8px; text-align:center;">No hay productos en esta factura</td>
            </tr>';
    }

    $total = $subtotal;

    $html_productos .= '
        </tbody>
    </table>
    <br>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td></td>
            <td>
                <table border="1" cellpadding="5" cellspacing="0" style="width: 200px; float: right; border-collapse: collapse;">
                    <tr>
                        <td style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:1px solid #004085; border-right:none; padding:6px 8px;;"><strong>Total:</strong></td>
                        <td style="border-top:1px solid #004085; border-bottom:1px solid #004085; border-left:none; border-right:1px solid #004085; padding:6px 8px;;"><strong>' . number_format($total, 2) . '€</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>';

    $pdf->writeHTML($html_productos, true, false, true, false, '');

    // Pie de página
    $html_footer = '
    <br><br>
    <div style="text-align:center; font-size:8pt; color:#666; margin-top:20px; border-top:1px solid #ddd; padding-top:10px;">
        <p>Gracias por confiar en PymeCode</p>
        <p>Esta factura ha sido generada automáticamente, para más información, contacte con nosotros</p>
    </div>';

    $pdf->writeHTML($html_footer, true, false, true, false, '');

    // Limpiar cualquier salida previa
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Configurar headers para la descarga
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Factura_' . $id_factura . '.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    // Generar el PDF
    $pdf->Output('Factura_' . $id_factura . '.pdf', 'I');

} catch (Exception $e) {
    echo "Error al generar el PDF: " . $e->getMessage();
} finally {
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>