function Eliminar_cliente(id_cliente) {
    // Prevenir el comportamiento predeterminado del formulario
    event.preventDefault();
    
    // Mostrar un alert de confirmación
    if (confirm('¿Está seguro que desea eliminar este cliente?')) {
        // Si el usuario confirma, procede con la eliminación
        $.ajax({
            type: 'POST',
            url: 'funciones/eliminar_cliente.php',
            data: { id_cliente: id_cliente },
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                
                if (response.success) {
                    alert(response.message);
                    window.location.href = 'clientes.php'; // Redirigir a la lista de clientes
                } else {
                    alert('Error al eliminar al cliente: ' + response.message);
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