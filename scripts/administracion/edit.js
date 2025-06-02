/******************************************
 * OBTENER LA URL ACTUAL Y SUS PARÁMETROS *
 ******************************************/

import { rutaRaiz } from "../../rutas.js";

/******************************************
 * OBTENER EL VALOR DEL PARÁMETRO "ID"    *
 ******************************************/

/******************************************
 * FUNCIÓN PARA OBTENER DATOS DE LA API   *
 ******************************************/
async function fetchData(id) {
  try {
    console.log(rutaRaiz + "/api.php?id=" + id);
    /**********************************
     * CONSTRUIR LA URL DE LA API     *
     **********************************/
    const url = rutaRaiz + "/api.php?id=" + id; // Construye la URL de la API con el ID proporcionado

    /**********************************
     * REALIZAR LA LLAMADA A LA API   *
     **********************************/
    const response = await fetch(url); // Realiza una solicitud HTTP GET a la API

    /*******************************************************************
     * VERIFICAR SI LA RESPUESTA ES EXITOSA (STATUS CODE 200-299)      *
     *******************************************************************/
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`); // Lanza un error si la respuesta no es exitosa
    }

    /***************************************
     * PARSEAR LA RESPUESTA COMO JSON      *
     ***************************************/
    const data = await response.json(); // Convierte la respuesta en un objeto JSON

    if (Array.isArray(data) && data.length === 0) {
      alert("No hay resultados con esa ID");
    }

    agregarLibroAFormulario(data[0]);

    /**********************************************************************************
     * PROCESAR Y MOSTRAR LOS DATOS EN EL DOM                                         *
     **********************************************************************************/
    console.log(data); // Muestra los datos en la consola para depuración

    // Itera sobre cada libro en los datos recibidos
  } catch (error) {
    /************************
     * MANEJO DE ERRORES    *
     ************************/
    console.error("Hubo un problema con la solicitud fetch:", error); // Muestra el error en la consola
  }
}

function agregarLibroAFormulario(libro) {
  document.getElementById("titulo").value = libro.titulo || "";
  document.getElementById("autor").value = libro.autor || "";
  document.getElementById("descripcion").value = libro.descripcion || "";
  document.getElementById("recomendado").value = libro.recomendado || "no";
  document.getElementById("trimestre").value = libro.trimestre || "";
  document.getElementById("isbn").value = libro.isbn || "";
  document.getElementById("cantidad").value = libro.cantidad || 0;
}

const editForm = document.getElementById("edit-form");

editForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const id = document.querySelector("#editID").value;

  fetchData(id);
});

/* FUNCION EDITAR LIBRO */

/******************************************
 * FUNCIÓN PARA EDITAR UN LIBRO EN LA API *
 ******************************************/
async function editarLibro(id) {
  try {
    // Obtener los valores del formulario
    const libroData = {
      titulo: document.getElementById("titulo").value.trim(),
      autor: document.getElementById("autor").value.trim(),
      descripcion: document.getElementById("descripcion").value.trim(),
      recomendado: document.getElementById("recomendado").value,
      trimestre: document.getElementById("trimestre").value || null,
      isbn: document.getElementById("isbn").value.trim() || null,
      cantidad: parseInt(document.getElementById("cantidad").value) || 0,
      categoria: document.getElementById("categorias").value,
      subcategoria: document.getElementById("subcategorias").value,
    };

    // Validación básica
    if (!libroData.titulo || !libroData.autor || !libroData.descripcion) {
      alert(
        "Por favor complete los campos obligatorios: Título, Autor y Descripción"
      );
      return;
    }

    // Configurar la petición PUT
    const response = await fetch(`${rutaRaiz}/api.php?id=${id}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(libroData),
    });

    // Verificar la respuesta
    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || "Error al actualizar el libro");
    }

    const result = await response.json();
    console.log("Libro actualizado:", result);
    alert("Libro actualizado correctamente");

    // Opcional: recargar los datos o limpiar el formulario
    // fetchData(id); // Para ver los cambios inmediatamente
  } catch (error) {
    console.error("Error al editar el libro:", error);
    alert(`Error al editar el libro: ${error.message}`);
  }
}

const bookForm = document.getElementById("book-form");

bookForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const id = document.getElementById("editID").value;
  editarLibro(id);
});
