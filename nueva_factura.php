<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["rol"])) {
    // Si no hay sesión activa, redirigir al login
    header("Location: login.php");
    exit;
}

// Obtener información del usuario de la sesión
$rol = $_SESSION["rol"];
$nombreUsuario = $_SESSION["usuario_nombre"];

if ($_SESSION["rol"] == "empleado") {
    $id_empresa_empleado = $_SESSION["usuario_empresa_id"];
} else {
    $id_empresa_empleado = $_SESSION["usuario_id"];
}

require_once "conexion.php";
$conexion = conexionBD();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/nueva_factura.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/nueva_factura.js"></script>
    <title>Nueva factura</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
        <nav class="nav-links">
            <a href="facturas.php">Volver</a>
        </nav>
    </header>

    <div class="tarjeta-crear-factura">
        <h2>Crear nueva factura</h2>
        <form id="formulario-crear-factura" onsubmit="Crear_factura(event)">
            <div class="campo-formulario">
                <label for="select-clientes">Cliente:</label>
                <select name="id_cliente" id="select-clientes" required>
                    <option value="">Seleccione un cliente</option>
                    <?php
                    // Cargar clientes desde la base de datos según la empresa
                    $query = "SELECT id_cliente, nombre_cliente FROM clientes WHERE id_empresa_cliente = ?";
                    $stmt = $conexion->prepare($query);
                    $stmt->bind_param("s", $id_empresa_empleado);
                    $stmt->execute();
                    $resultado = $stmt->get_result();

                    while ($cliente = $resultado->fetch_assoc()) {
                        echo "<option value='" . $cliente['id_cliente'] . "'>" . $cliente['nombre_cliente'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <p class="titulo-detalles-factura">Detalles de la factura</p>
            <div class="tabla-detalles">
                <table id="tabla-detalles-factura">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="detalles-factura">
                        <!-- Aquí se añadirán dinámicamente los detalles -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="total-factura-txt"><strong>Total:</strong></td>
                            <td><span id="total-factura">0.00</span> €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="agregar-detalle">
                <div class="campo-formulario fila-flex">
                    <select id="producto-nuevo" class="select-producto">
                        <option value="">Seleccione un producto</option>
                        <?php
                        // Cargar productos desde la base de datos según la empresa
                        $query = "SELECT id_producto, nombre_producto, precio_producto, stock_producto FROM productos WHERE id_empresa_producto = ?";
                        $stmt = $conexion->prepare($query);
                        $stmt->bind_param("s", $id_empresa_empleado);
                        $stmt->execute();
                        $resultado = $stmt->get_result();

                        while ($producto = $resultado->fetch_assoc()) {
                            echo "<option value='" . $producto['id_producto'] . "' 
                                data-precio='" . $producto['precio_producto'] . "' 
                                data-stock='" . $producto['stock_producto'] . "'>
                                " . $producto['nombre_producto'] . " - " . $producto['precio_producto'] . "€ (Stock: " . $producto['stock_producto'] . ")
                                </option>";
                        }
                        ?>
                    </select>
                    <input type="number" id="cantidad-nueva" min="1" placeholder="Cantidad" class="input-cantidad">
                    <button type="button" id="btn-agregar-detalle" class="btn-agregar" onclick="agregarDetalle()">
                        <i class="fas fa-plus"></i> Añadir producto
                    </button>
                </div>
            </div>

            <div class="campo-formulario botonera">
                <button type="submit" class="boton-enviar">Crear factura</button>
            </div>
        </form>
    </div>


    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>

    <script>
        let detallesFactura = [];

        // Al cambiar el producto seleccionado
        $('#producto-nuevo').change(function () {
            const stock = $(this).find(':selected').data('stock');
            $('#cantidad-nueva').attr('max', stock);
        });

        // Función para agregar un detalle a la factura
        function agregarDetalle() {
            const productoSelect = document.getElementById('producto-nuevo');
            const cantidadInput = document.getElementById('cantidad-nueva');

            const productoId = productoSelect.value;
            const productoOption = productoSelect.options[productoSelect.selectedIndex];
            const productoNombre = productoOption.text.split(' - ')[0];
            const precioPorUnidad = parseFloat(productoOption.dataset.precio);
            const stockDisponible = parseInt(productoOption.dataset.stock);
            const cantidad = parseInt(cantidadInput.value);

            // Validaciones
            if (!productoId) {
                alert('Por favor, seleccione un producto');
                return;
            }

            if (!cantidad || isNaN(cantidad) || cantidad <= 0) {
                alert('Por favor, ingrese una cantidad válida');
                return;
            }

            if (cantidad > stockDisponible) {
                alert(`Stock insuficiente. Disponible: ${stockDisponible}`);
                return;
            }

            // Verificar si el producto ya está en la factura
            const productoExistente = detallesFactura.findIndex(d => d.productoId === productoId);
            if (productoExistente !== -1) {
                // Si ya existe, actualizar la cantidad
                const nuevaCantidad = detallesFactura[productoExistente].cantidad + cantidad;
                if (nuevaCantidad > stockDisponible) {
                    alert(`Stock insuficiente. Ya tiene ${detallesFactura[productoExistente].cantidad} unidades agregadas.`);
                    return;
                }
                detallesFactura[productoExistente].cantidad = nuevaCantidad;
                detallesFactura[productoExistente].subtotal = nuevaCantidad * precioPorUnidad;
            } else {
                // Si no existe, agregar nuevo detalle
                detallesFactura.push({
                    productoId,
                    productoNombre,
                    precioPorUnidad,
                    cantidad,
                    subtotal: cantidad * precioPorUnidad
                });
            }

            // Limpiar campos
            productoSelect.value = '';
            cantidadInput.value = '';

            // Actualizar la tabla
            actualizarTablaDetalles();
        }

        // Función para eliminar un detalle
        function eliminarDetalle(index) {
            detallesFactura.splice(index, 1);
            actualizarTablaDetalles();
        }

        // Función para actualizar la tabla de detalles
        function actualizarTablaDetalles() {
            const tbody = document.getElementById('detalles-factura');
            tbody.innerHTML = '';

            let total = 0;

            detallesFactura.forEach((detalle, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${detalle.productoNombre}</td>
                    <td>${detalle.precioPorUnidad.toFixed(2)} €</td>
                    <td>${detalle.cantidad}</td>
                    <td>${detalle.subtotal.toFixed(2)} €</td>
                    <td>
                        <button type="button" class="btn-eliminar" onclick="eliminarDetalle(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);

                total += detalle.subtotal;
            });

            // Actualizar el total
            document.getElementById('total-factura').textContent = total.toFixed(2);
        }
    </script>

</body>

</html>