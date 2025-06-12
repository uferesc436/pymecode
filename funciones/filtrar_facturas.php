<?php
session_start();
require_once "../conexion.php";
$conexion = conexionBD();

if ($_SESSION["rol"] == "empleado") {
    $id_empresa_empleado = $_SESSION["usuario_empresa_id"];
} else {
    $id_empresa_empleado = $_SESSION["usuario_id"];
}

$filtros = [];

if (!empty($_POST['fecha_factura'])) {
    $filtros[] = "fecha_factura LIKE '" . mysqli_real_escape_string($conexion, $_POST['fecha_factura']) . "%'";
}
if (!empty($_POST['total_factura'])) {
    $filtros[] = "total_factura LIKE '" . mysqli_real_escape_string($conexion, $_POST['total_factura']) . "%'";
}
if (!empty($_POST['id_cliente_factura'])) {
    $filtros[] = "id_cliente_factura LIKE '" . mysqli_real_escape_string($conexion, $_POST['id_cliente_factura']) . "%'";
}

// Mostramos todas las facturas, pero solo de aquellos clientes que pertenecen a la empresa que está en sesión
$consulta = "SELECT f.* 
                FROM facturas f
                INNER JOIN clientes c ON f.id_cliente_factura = c.id_cliente
                WHERE c.id_empresa_cliente = '" . mysqli_real_escape_string($conexion, $id_empresa_empleado) . "'";

if (!empty($filtros)) {
    $consulta .= " AND " . implode(" AND ", $filtros);
}

if ($result = mysqli_query($conexion, $consulta)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='tabla_facturas'>
                <thead>
                    <tr>
                        <th id='TR_NOMBRE' class='th_lista_facturas th_inicio th_l'>FECHA</th>
                        <th id='TR_NOMBRE' class='th_lista_facturas th_inicio th_l'>TOTAL</th>
                        <th id='TR_NOMBRE' class='th_lista_facturas th_inicio th_l'>DNI CLIENTE</th>
                        <th id='TR_EDITAR' class='th_lista_facturas th_xs'>ACCIONES</th>
                    </tr>
                </thead>
                <tbody class='tabla_facturas_contenido'>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td class='td_lista_facturas td_l'>" . htmlspecialchars($row["fecha_factura"]) . "</td>";
            echo "<td class='td_lista_facturas td_l'>" . htmlspecialchars($row["total_factura"]) . "€</td>";
            echo "<td class='td_lista_facturas td_l'>" . htmlspecialchars($row["id_cliente_factura"]) . "</td>";
            echo "<td class='td_lista_facturas td_l'>
                    <div class='acciones-factura'>
                        <button type='button' class='boton-enviar eliminar' onclick='Eliminar_factura(\"" . htmlspecialchars($row["id_factura"]) . "\")'>Eliminar</button>
                        <form action='funciones/generar_pdf_factura.php' method='POST' target='_blank'>
                            <input type='hidden' name='id_factura' value='" . htmlspecialchars($row["id_factura"]) . "'>
                            <button class='boton-enviar eliminar' type='submit'>Descargar PDF</button>
                        </form>
                    </div>
                </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No hay facturas registrados.";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conexion);
}

?>