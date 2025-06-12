<?php
function conexionBD()
{
    $host = 'localhost';
    $basededatos = 'pymecontrol';
    $usuario = 'root';
    $password = '';

    $conexion = new mysqli($host, $usuario, $password, $basededatos);

    if ($conexion->connect_error) {
        die("Error en la conexión a la base de datos: " . $conexion->connect_error);
    }

    return $conexion;
}
?>
