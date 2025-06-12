function Registrar_empresa(event) {
    event.preventDefault(); // Prevenir el envío normal del formulario
    
    const form = document.getElementById("formulario-registro");
    let isValid = true;

    // Validar campos requeridos
    form.querySelectorAll("[required]").forEach((field) => {
        if (!field.value.trim()) {
            isValid = false;
        }
    });

    // Validación del NIF (Formato ejemplo: A12345678)
    const nif = form.querySelector('input[name="nif_empresa"]').value.trim();
    const nifPattern = /^[A-Za-z]{1}[0-9]{8}$/;
    if (nif && !nifPattern.test(nif)) {
        alert("El NIF debe empezar con una letra seguida de 8 números.");
        isValid = false;
    }

    // Validación del correo electrónico
    const correo = form.querySelector('input[name="correo_empresa"]').value.trim();
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (correo && !emailPattern.test(correo)) {
        alert("Por favor, ingresa un correo electrónico válido.");
        isValid = false;
    }

    // Validación del teléfono (solo números, 9 dígitos)
    const telefono = form.querySelector('input[name="telefono_empresa"]').value.trim();
    const phonePattern = /^[0-9]{9}$/;
    if (telefono && !phonePattern.test(telefono)) {
        alert("El teléfono debe tener 9 dígitos.");
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
        url: "funciones/registrar_empresa.php", 
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    alert("¡Bienvenido! Registro exitoso.");
                    window.location.href = "login.php";
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