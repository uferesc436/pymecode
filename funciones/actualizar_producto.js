function Editar_producto(id_producto) {
  const form = document.getElementById("formulario-editar-producto");
  let isValid = true;

  // Validar campos requeridos
  form.querySelectorAll("[required]").forEach((field) => {
    if (!field.value.trim()) {
      isValid = false;
    }
  });

  // Si algún campo está vacío o tiene formato incorrecto, mostrar alerta y detener el envío
  const precioField = form.querySelector("#precio_producto");
  if (parseFloat(precioField.value) < 0) {
    alert("El precio no puede ser negativo.");
    isValid = false;
  }

  const stockField = form.querySelector("#stock_producto");
  if (parseInt(stockField.value) < 0) {
    alert("El stock no puede ser negativo.");
    isValid = false;
  }

  if (!isValid) {
    return;
  }

  // Enviar los datos del formulario con AJAX
  const formData = new FormData(form);

  $.ajax({
    type: "POST",
    url: "funciones/actualizar_producto.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      try {
        const result = JSON.parse(response);
        if (result.success) {
          alert("Producto actualizado con éxito.");
          window.location.href = "productos.php";
        } else {
          alert(result.message || "Error al actualizar el producto.");
        }
      } catch (e) {
        console.error("Error al procesar la respuesta:", response);
        alert("Error en el procesamiento de la respuesta.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error AJAX:", status, error);
      alert("Error al conectar con el servidor.");
    },
  });
}
