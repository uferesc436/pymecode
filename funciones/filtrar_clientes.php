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

if (!empty($_POST['dni_cliente'])) {
    $filtros[] = "id_cliente LIKE '" . mysqli_real_escape_string($conexion, $_POST['dni_cliente']) . "%'";
}
if (!empty($_POST['nombre_cliente'])) {
    $filtros[] = "nombre_cliente LIKE '" . mysqli_real_escape_string($conexion, $_POST['nombre_cliente']) . "%'";
}
if (!empty($_POST['email_cliente'])) {
    $filtros[] = "email_cliente LIKE '" . mysqli_real_escape_string($conexion, $_POST['email_cliente']) . "%'";
}

$consulta = "SELECT * FROM clientes WHERE id_empresa_cliente = '$id_empresa_empleado'";
if (!empty($filtros)) {
    $consulta .= " AND " . implode(" AND ", $filtros);
}

if ($result = mysqli_query($conexion, $consulta)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='tabla_clientes'>
                <thead>
                    <tr>
                        <th id='TR_NOMBRE' class='th_lista_clientes th_inicio th_l'>DNI</th>
                        <th id='TR_NOMBRE' class='th_lista_clientes th_inicio th_l'>NOMBRE</th>
                        <th id='TR_NOMBRE' class='th_lista_clientes th_inicio th_l'>EMAIL</th>
                        <th id='TR_NOMBRE' class='th_lista_clientes th_inicio th_l'>TELÉFONO</th>
                        <th id='TR_EDITAR' class='th_lista_clientes th_xs'>ACCIONES</th>
                    </tr>
                </thead>
                <tbody class='tabla_clientes_contenido'>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td class='td_lista_clientes td_l'>" . htmlspecialchars($row["id_cliente"]) . "</td>";
            echo "<td class='td_lista_clientes td_l'>" . htmlspecialchars($row["nombre_cliente"]) . "</td>";
            echo "<td class='td_lista_clientes td_l'>" . htmlspecialchars($row["email_cliente"]) . "</td>";
            echo "<td class='td_lista_clientes td_l'>" . htmlspecialchars($row["telefono_cliente"]) . "</td>";
            echo "<td class='td_lista_clientes td_l'>
                    <div class='acciones-cliente'>
                        <form action='editar_cliente.php' method='POST'>
                            <input type='hidden' name='id_cliente' value='" . htmlspecialchars($row["id_cliente"]) . "'>
                            <button type='submit' class='boton-enviar'>Editar</button>
                        </form>
                        <button type='button' class='boton-enviar eliminar' onclick='Eliminar_cliente(\"" . htmlspecialchars($row["id_cliente"]) . "\")'>Eliminar</button>
                    </div>
                </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No hay clientes registrados.";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conexion);
}
?>
