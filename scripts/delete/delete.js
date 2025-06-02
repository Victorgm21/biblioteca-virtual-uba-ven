const formularioDelete = document.getElementById("delete-form");
const inputId = document.getElementById("delete-id");
import { rutaRaiz } from "../../rutas.js";

async function verificarSiExisteID(id) {
  try {
    /**********************************
     * // HACEMOS LA LLAMADA A LA API *
     **********************************/

    let url = rutaRaiz + "/api.php?id=" + id;
    const response = await fetch(url);

    /*******************************************************************
     * // VERIFICAMOS SI LA RESPUESTA ES EXITOSA (STATUS CODE 200-299) *
     *******************************************************************/

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (Array.isArray(data) && data.length === 0) {
      return false; // No se encontró la ID
    } else {
      return true; // La ID existe
    }
  } catch (error) {
    alert("Hubo un problema con la solicitud fetch:", error);
    return false;
  }
}

async function eliminarLibro(id) {
  try {
    /**********************************
     * // HACEMOS LA LLAMADA A LA API *
     **********************************/

    // URL de la API para eliminar un libro
    let url = rutaRaiz + "/api.php?id=" + id;

    // Configuración de la solicitud (método DELETE)
    const options = {
      method: "DELETE", // Usamos el método DELETE para eliminar el recurso
      headers: {
        "Content-Type": "application/json", // Especificamos que enviamos JSON
      },
    };

    // Hacemos la solicitud a la API
    const response = await fetch(url, options);

    /*******************************************************************
     * // VERIFICAMOS SI LA RESPUESTA ES EXITOSA (STATUS CODE 200-299) *
     *******************************************************************/

    if (!response.ok) {
      // Si la respuesta no es exitosa, lanzamos un error
      throw new Error(
        `Error en la solicitud: ${response.status} ${response.statusText}`
      );
    }

    /*****************************************
     * // PROCESAMOS LA RESPUESTA DE LA API *
     *****************************************/

    const data = await response.json(); // Convertimos la respuesta a JSON

    // Mostramos un mensaje de éxito
    alert(data.message || "Libro eliminado correctamente.");

    // Actualizamos la interfaz de usuario (eliminamos el libro de la lista)
    const libroElement = document.querySelector(`[data-id="${id}"]`);
    if (libroElement) {
      libroElement.remove(); // Eliminamos el elemento del DOM
    }

    return true; // Indicamos que la operación fue exitosa
  } catch (error) {
    // Mostramos un mensaje de error
    alert("Hubo un problema al eliminar el libro: " + error.message);
    return false; // Indicamos que la operación falló
  }
}

formularioDelete.addEventListener("submit", (e) => {
  e.preventDefault();
  const idLibro = inputId.value;
  verificarSiExisteID(idLibro).then((existe) => {
    switch (existe) {
      case true:
        eliminarLibro(idLibro);
        break;
      case false:
        alert("No existe la ID a borrar");
        break;
      default:
        alert("Hubo un error al verificar la ID");
        break;
    }
  });
});
