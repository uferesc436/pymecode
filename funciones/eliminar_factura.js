function Eliminar_factura(id_factura) {
    // Prevenir el comportamiento predeterminado del formulario
    event.preventDefault();
    
    // Mostrar un alert de confirmación
    if (confirm('¿Está seguro que desea eliminar esta factura?')) {
        // Si el usuario confirma, procede con la eliminación
        $.ajax({
            type: 'POST',
            url: 'funciones/eliminar_factura.php',
            data: { id_factura: id_factura },
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                
                if (response.success) {
                    alert(response.message);
                    window.location.href = 'facturas.php'; // Redirigir a la lista de facturas
                } else {
                    alert('Error al eliminar la factura: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud:", error);
                console.log("Respuesta completa:", xhr.responseText);
                alert('No tienes permisos para hacer esto.');
            }
        });
    } else {
        // Si el usuario cancela, no hacer nada
        console.log("Eliminación cancelada por el usuario");
    }
}