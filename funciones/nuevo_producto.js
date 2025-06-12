function Crear_producto(event) {
  event.preventDefault();

  const form = document.getElementById("formulario-crear-producto");
  let isValid = true;

  // Validación básica
  form.querySelectorAll("[required]").forEach((field) => {
    if (!field.value.trim()) {
      isValid = false;
    }
  });

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

  if (!isValid) return;

  const formData = new FormData(form);
  $.ajax({
    type: "POST",
    url: "funciones/guardar_producto.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      console.log("Respuesta del servidor:", response); // 👈 DEBUG
      try {
        const result = JSON.parse(response);
        if (result.success) {
          alert("Producto creado con éxito.");
          window.location.href = "productos.php";
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
    },
  });
}

// Vinculación al formulario
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formulario-crear-producto");
  if (form) {
    form.addEventListener("submit", Crear_producto);
  }
});
