/******************************************
 * OBTENER LA URL ACTUAL Y SUS PARÁMETROS *
 ******************************************/
import { rutaRaiz } from "../rutas.js";
const urlParametros = new URL(window.location.href); // Crea un objeto URL con la URL actual

/******************************************
 * OBTENER EL VALOR DEL PARÁMETRO "ID"    *
 ******************************************/
const id = urlParametros.searchParams.get("id"); // Extrae el valor del parámetro "id" de la URL

/******************************************
 * FUNCIÓN PARA OBTENER DATOS DE LA API   *
 ******************************************/
async function fetchData(id) {
  try {
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

    /**********************************************************************************
     * PROCESAR Y MOSTRAR LOS DATOS EN EL DOM                                         *
     **********************************************************************************/
    console.log(data); // Muestra los datos en la consola para depuración

    // Itera sobre cada libro en los datos recibidos
    data.forEach((libro) => {
      // Crea un bloque HTML para cada libro
      const libroHTML = `
        <div class="libro" id="${libro.id}">
          <div class="imagen-container">
            <img
              src="${rutaRaiz + "/uploads/" + libro.imagen}"
              alt="${libro.autor}"
            />
          </div>
          <div class="libro-info">
            <h3 class="libro-titulo">${libro.titulo} (#${libro.id})</h3>
            <p class="libro-autor">${libro.autor}</p>
            <div class="etiquetas">
            </div>
            <p class="libro-descripcion">
              ${libro.descripcion}
            </p>
          </div>
        </div>
      `;

      // Agrega el bloque HTML del libro al contenedor de libros en el DOM
      document.querySelector(".libro-container").innerHTML += libroHTML;
      let etiqueta = "";
      const contenedorEtiquetas = document.querySelector(".etiquetas");
      // ETIQUETA DESCARGA PDF
      if (data[0].documento != "") {
        const etiqueta = `<a href="${
          rutaRaiz + "/uploads/" + libro.documento
        }" class="boton-descargar" download="${libro.documento}">Descargar</a>`;
        contenedorEtiquetas.innerHTML += etiqueta;
      }
      // ETIQUETA CANTIDAD
      if (data[0].cantidad > 0) {
        const etiqueta = `<span class="etiqueta" data-tipo="disponibilidad">${data[0].cantidad} ejemplar en biblioteca</span>`;
        contenedorEtiquetas.innerHTML += etiqueta;
      }
      // ETIQUETA RECOMENDADO POR PROFESOR
      if (data[0].recomendado == "si") {
        const etiqueta = `<span class="etiqueta" data-tipo="recomendado">Recomendado por profesores</span>`;
        contenedorEtiquetas.innerHTML += etiqueta;
      }
      // ETIQUETA TRIMESTRE
      if (data[0].trimestre > 0) {
        const etiqueta = `<span class="etiqueta" data-tipo="trimestre">Trimestre ${data[0].trimestre}</span>`;
        contenedorEtiquetas.innerHTML += etiqueta;
      }
      // ETIQUETA TRIMESTRE
      if (data[0].categoria != "Sin categoría" && data[0].categoria != "") {
        const etiqueta = `<span class="etiqueta" data-tipo="categoria">${data[0].categoria}</span>`;
        contenedorEtiquetas.innerHTML += etiqueta;
      }
      // ETIQUETA TRIMESTRE
      if (
        data[0].subcategoria != "Sin subcategoría" &&
        data[0].subcategoria != ""
      ) {
        const etiqueta = `<span class="etiqueta" data-tipo="subcategoria">${data[0].subcategoria}</span>`;
        contenedorEtiquetas.innerHTML += etiqueta;
      }
    });
  } catch (error) {
    /************************
     * MANEJO DE ERRORES    *
     ************************/
    console.error("Hubo un problema con la solicitud fetch:", error); // Muestra el error en la consola
  }
}

/******************************************
 * FUNCIÓN PARA INICIAR LA BÚSQUEDA DE    *
 * LIBROS CON EL ID OBTENIDO DE LA URL    *
 ******************************************/
function buscarLibros(id) {
  fetchData(id); // Llama a la función fetchData con el ID proporcionado
}

/******************************************
 * INICIAR LA BÚSQUEDA DE LIBROS          *
 ******************************************/
buscarLibros(id); // Ejecuta la función buscarLibros con el ID extraído de la URL
