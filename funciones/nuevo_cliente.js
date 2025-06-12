function Crear_cliente(event) {
    // Prevenir el comportamiento predeterminado del formulario
    event.preventDefault();    
    const form = document.getElementById("formulario-crear-cliente");
    let isValid = true;

    // Validar campos requeridos
    form.querySelectorAll("[required]").forEach((field) => {
        if (!field.value.trim()) {
            isValid = false;
        }
    });

    // Validación del DNI (Formato ejemplo: 12345678A)
    const dni = form.querySelector('input[name="id_cliente"]').value.trim();
    const dniPattern = /^[0-9]{8}[A-Za-z]$/;
    if (dni && !dniPattern.test(dni)) {
        alert("El DNI debe tener 8 números seguidos de una letra.");
        isValid = false;
    }

    // Validación del correo electrónico
    const correo = form.querySelector('input[name="email_cliente"]').value.trim();
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (correo && !emailPattern.test(correo)) {
        alert("Por favor, ingresa un correo electrónico válido.");
        isValid = false;
    }

    // Validación del número de teléfono (Formato ejemplo: 123456789)
    const telefono = form.querySelector('input[name="telefono_cliente"]').value.trim();
    const telefonoPattern = /^[0-9]{9}$/;
    if (telefono && !telefonoPattern.test(telefono)) {
        alert("El teléfono debe tener 9 números.");
        isValid = false;
    }

    // Si algún campo está vacío o tiene formato incorrecto, mostrar alerta y detener el envío
    if (!isValid) {
        return;
    }

    // Enviar los datos del formulario con AJAX
    const formData = new FormData(form);
    $.ajax({
        type: "POST",
        url: "funciones/guardar_cliente.php", 
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    alert("Cliente creado con éxito.");
                    window.location.href = "clientes.php";
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