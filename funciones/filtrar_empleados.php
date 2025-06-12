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

if (!empty($_POST['dni_empleado'])) {
    $filtros[] = "id_empleado LIKE '" . mysqli_real_escape_string($conexion, $_POST['dni_empleado']) . "%'";
}
if (!empty($_POST['nombre_empleado'])) {
    $filtros[] = "nombre_empleado LIKE '" . mysqli_real_escape_string($conexion, $_POST['nombre_empleado']) . "%'";
}
if (!empty($_POST['email_empleado'])) {
    $filtros[] = "email_empleado LIKE '" . mysqli_real_escape_string($conexion, $_POST['email_empleado']) . "%'";
}

$consulta = "SELECT * FROM empleados WHERE id_empresa_empleado = '$id_empresa_empleado'";
if (!empty($filtros)) {
    $consulta .= " AND " . implode(" AND ", $filtros);
}

if ($result = mysqli_query($conexion, $consulta)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='tabla_empleados'>
                <thead>
                    <tr>
                        <th id='TR_NOMBRE' class='th_lista_empleados th_inicio th_l'>DNI</th>
                        <th id='TR_NOMBRE' class='th_lista_empleados th_inicio th_l'>NOMBRE</th>
                        <th id='TR_NOMBRE' class='th_lista_empleados th_inicio th_l'>EMAIL</th>
                        <th id='TR_EDITAR' class='th_lista_empleados th_xs'>ACCIONES</th>
                    </tr>
                </thead>
                <tbody class='tabla_empleados_contenido'>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td class='td_lista_empleados td_l'>" . htmlspecialchars($row["id_empleado"]) . "</td>";
            echo "<td class='td_lista_empleados td_l'>" . htmlspecialchars($row["nombre_empleado"]) . "</td>";
            echo "<td class='td_lista_empleados td_l'>" . htmlspecialchars($row["email_empleado"]) . "</td>";
            echo "<td class='td_lista_empleados td_l'>
                    <div class='acciones-empleado'>
                        <form action='editar_empleado.php' method='POST'>
                            <input type='hidden' name='empleado_id' value='" . htmlspecialchars($row["id_empleado"]) . "'>
                            <button type='submit' class='boton-enviar'>Editar</button>
                        </form>
                        <button type='button' class='boton-enviar eliminar' onclick='Eliminar_empleado(\"" . htmlspecialchars($row["id_empleado"]) . "\")'>Eliminar</button>
                    </div>
                </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No hay empleados registrados.";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conexion);
}
?>
