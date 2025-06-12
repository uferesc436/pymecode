<?php
session_start();
require_once "../conexion.php";
$conexion = conexionBD();

// Determinar el ID de la empresa para filtrar productos
if ($_SESSION["rol"] == "empleado") {
    $id_empresa = $_SESSION["usuario_empresa_id"];
} else {
    $id_empresa = $_SESSION["usuario_id"];
}

$filtros = [];

// Filtrar por ID de producto
if (!empty($_POST['id_producto'])) {
    $filtros[] = "id_producto LIKE '" . mysqli_real_escape_string($conexion, $_POST['id_producto']) . "%'";
}

// Filtrar por nombre de producto
if (!empty($_POST['nombre_producto'])) {
    $filtros[] = "nombre_producto LIKE '" . mysqli_real_escape_string($conexion, $_POST['nombre_producto']) . "%'";
}

// Filtrar por precio mínimo
if (!empty($_POST['precio_min_producto'])) {
    $precio_min = mysqli_real_escape_string($conexion, $_POST['precio_min_producto']);
    if (is_numeric($precio_min)) {
        $filtros[] = "precio_producto >= " . $precio_min;
    }
}

// Filtrar por precio máximo
if (!empty($_POST['precio_max_producto'])) {
    $precio_max = mysqli_real_escape_string($conexion, $_POST['precio_max_producto']);
    if (is_numeric($precio_max)) {
        $filtros[] = "precio_producto <= " . $precio_max;
    }
}

// Construir la consulta SQL
$consulta = "SELECT * FROM productos WHERE id_empresa_producto = '$id_empresa'";
if (!empty($filtros)) {
    $consulta .= " AND " . implode(" AND ", $filtros);
}

// Ejecutar la consulta
if ($result = mysqli_query($conexion, $consulta)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='tabla_productos'>
                <thead>
                    <tr>
                        <th id='TR_NOMBRE' class='th_lista_productos th_inicio th_l'>ID</th>
                        <th id='TR_NOMBRE' class='th_lista_productos th_inicio th_l'>NOMBRE</th>
                        <th id='TR_NOMBRE' class='th_lista_productos th_inicio th_l'>PRECIO</th>
                        <th id='TR_NOMBRE' class='th_lista_productos th_inicio th_l'>STOCK</th>
                        <th id='TR_EDITAR' class='th_lista_productos th_xs'>ACCIONES</th>
                    </tr>
                </thead>
                <tbody class='tabla_productos_contenido'>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td class='td_lista_productos td_l'>" . htmlspecialchars($row["id_producto"]) . "</td>";
            echo "<td class='td_lista_productos td_l'>" . htmlspecialchars($row["nombre_producto"]) . "</td>";
            echo "<td class='td_lista_productos td_l'>" . htmlspecialchars($row["precio_producto"]) . "€</td>";
            echo "<td class='td_lista_productos td_l'>" . htmlspecialchars($row["stock_producto"]) . "</td>";
            echo "<td class='td_lista_productos td_l'>
                    <div class='acciones-producto'>
                        <form action='editar_producto.php' method='POST'>
                            <input type='hidden' name='id_producto' value='" . htmlspecialchars($row["id_producto"]) . "'>
                            <button type='submit' class='boton-enviar'>Editar</button>
                        </form>
                        <button type='button' class='boton-enviar eliminar' onclick='Eliminar_producto(\"" . htmlspecialchars($row["id_producto"]) . "\")'>Eliminar</button>
                    </div>
                </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No hay productos registrados.";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conexion);
}
?>