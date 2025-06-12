function Crear_factura(event) {
    // Prevenir el comportamiento predeterminado del formulario
    event.preventDefault();
    const form = document.getElementById("formulario-crear-factura");
    let isValid = true;

    // Validar campos requeridos
    form.querySelectorAll("[required]").forEach((field) => {
        if (!field.value.trim()) {
            isValid = false;
        }
    });

    // Validar que hay un cliente seleccionado
    const clienteId = document.getElementById('select-clientes').value;
    if (!clienteId) {
        alert('Por favor, seleccione un cliente');
        return;
    }

    // Validar que hay al menos un producto
    if (detallesFactura.length === 0) {
        alert('Debe agregar al menos un producto a la factura');
        return;
    }

    // Enviar los datos del formulario con AJAX
    const formData = new FormData(form);

    // Añadir los productos al FormData como JSON
    formData.append('detalles', JSON.stringify(detallesFactura));

    $.ajax({
        type: "POST",
        url: "funciones/guardar_factura.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    alert("Factura creada con éxito.");
                    window.location.href = "facturas.php";
                } else {
                    alert(result.message || "Error en el registro.");
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", response);
                alert("Error en el procesamiento de la respuesta.");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error AJAX:", status, error);
            alert("Error al conectar con el servidor.");
        }
    });
}