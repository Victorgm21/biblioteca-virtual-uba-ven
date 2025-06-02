import { rutaRaiz } from "../rutas.js";

document
  .getElementById("book-form")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    // Limpiar mensajes anteriores
    document.getElementById("response-message").innerHTML = "";
    document.getElementById("response-message-error").innerHTML = "";

    const formData = new FormData();

    // Obtener valores del formulario
    const imagen = document.getElementById("imagen").files[0];
    const documento = document.getElementById("documento").files[0];
    const titulo = document.getElementById("titulo").value;
    const autor = document.getElementById("autor").value;
    const descripcion = document.getElementById("descripcion").value;
    const recomendado = document.getElementById("recomendado").value;

    const trimestre = document.getElementById("trimestre").value;
    const isbn = document.getElementById("isbn").value;
    const cantidad = document.getElementById("cantidad").value;
    // CATEGORIAS
    const categoria = document.getElementById("categorias").value;
    const subCategoria = document.getElementById("subcategorias").value;

    // Validar campos requeridos
    if (!imagen) {
      showError("La imagen del libro es requerida");
      return;
    }

    // Agregar campos al FormData
    formData.append("imagen", imagen);
    if (documento) {
      // Solo agregar documento si existe
      formData.append("documento", documento);
    }
    formData.append("titulo", titulo);
    formData.append("autor", autor);
    formData.append("descripcion", descripcion);
    formData.append("recomendado", recomendado);
    formData.append("trimestre", trimestre);
    formData.append("isbn", isbn);
    formData.append("cantidad", cantidad);
    // CATEGORIAS
    formData.append("categoria", categoria);
    formData.append("subcategoria", subCategoria);

    try {
      const response = await fetch(rutaRaiz + "/api.php", {
        method: "POST",
        body: formData,
      });

      // Primero obtenemos el texto de la respuesta para poder analizarlo
      const responseText = await response.text();

      try {
        // Intentamos parsear como JSON
        const data = JSON.parse(responseText);

        if (!response.ok) {
          throw new Error(data.error || "Error desconocido del servidor");
        }

        // Éxito
        window.scrollTo({ top: 0, behavior: "smooth" });
        document.getElementById(
          "response-message"
        ).innerHTML = `<p class="success-message">${data.message}</p>`;
        this.reset();
      } catch (e) {
        // Si no se puede parsear como JSON, mostramos la respuesta cruda
        throw new Error(responseText || "Respuesta no válida del servidor");
      }
    } catch (error) {
      showError(error.message);
    }
  });

function showError(message) {
  window.scrollTo({ top: 0, behavior: "smooth" });
  document.getElementById(
    "response-message-error"
  ).innerHTML = `<p class="error-message">Error: ${message}</p>`;
}
