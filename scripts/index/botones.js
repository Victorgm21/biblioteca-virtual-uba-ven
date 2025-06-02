const librosButton = document.getElementById("libros-button");
const librosRecomendadosButton = document.getElementById("recomendados-button");
const librosFisicosButton = document.getElementById("fisico-button");
const cargarMasButton = document.getElementById("cargar-mas");
const librosContainer = document.querySelector(".libros-grid");
import { rutaRaiz } from "../../rutas.js";

let contadorPagina = 0;
let filtro = "todos";

function insertarLibrosEnElGrid(data) {
  data.forEach((libro) => {
    const libroHTML = `
    <a href="${rutaRaiz}/libro.html?id=${
      libro.id
    }" class="libro-link" aria-label="Ver detalles de ${libro.titulo}">
      <div class="libro" id="${libro.id}">
        <img
          src="${rutaRaiz + "/uploads/" + libro.imagen}"
          alt="${libro.autor}"
        />
        <div class="libro-info">
          <h3 class="libro-titulo">${libro.titulo}</h3>
          <p class="libro-autor">${libro.autor}</p>
          <p class="libro-descripcion">
            ${libro.descripcion}
          </p>
        </div>
      </div>
    </a>
  `;
    librosContainer.innerHTML += libroHTML;
  });
}

async function buscarLibros(tipoBusqueda, offset = 0) {
  try {
    /**********************************
     * // HACEMOS LA LLAMADA A LA API *
     **********************************/

    let url = rutaRaiz + "/api.php";
    url += `?tipo=${tipoBusqueda}`;
    url += `&&offset=${offset}`;
    const response = await fetch(url);

    /*******************************************************************
     * // VERIFICAMOS SI LA RESPUESTA ES EXITOSA (STATUS CODE 200-299) *
     *******************************************************************/

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    /***************************************
     * PARSEAMOS LA RESPUESTA COMO JSON *
     ***************************************/
    const data = await response.json();

    if (Array.isArray(data) && data.length === 0) {
      alert("No hay resultados");
    }

    /******************************
     * INSERTAR LIBROS EN EL GRID *
     ******************************/
    insertarLibrosEnElGrid(data);
  } catch (error) {
    alert("Hubo un problema con la solicitud fetch:", error);
  }
}

/***************************************
 *             BUTON PARA              *
 * NO FILTRAR, BUSCAR TODOS LOS LIBROS *
 ***************************************/

librosButton.addEventListener("click", (e) => {
  contadorPagina = 0;
  librosContainer.innerHTML = "";
  filtro = "todos";
  buscarLibros("todos");
});

/*********************************************
 *                BOTON PARA                 *
 * FILTRAR TODOS LOS LIBROS POR RECOMENDADOS *
 *********************************************/

librosRecomendadosButton.addEventListener("click", (e) => {
  contadorPagina = 0;
  librosContainer.innerHTML = "";
  filtro = "recomendado";
  buscarLibros("recomendado");
});

/*****************************************************
 *                    BOTON PARA                     *
 * FILTRAR TODOS LOS LIBROS POR DISPONIBLE EN FISICO *
 *****************************************************/

librosFisicosButton.addEventListener("click", (e) => {
  contadorPagina = 0;
  librosContainer.innerHTML = "";
  filtro = "fisico";
  buscarLibros("fisico");
});

/*********************************
 *          BOTON PARA           *
 * CARGAR MAS LIBROS EN LA VISTA *
 *********************************/

cargarMasButton.addEventListener("click", (e) => {
  contadorPagina = contadorPagina + 1;
  switch (filtro) {
    case "todos":
      buscarLibros("todos", contadorPagina);
      break;
    case "recomendado":
      buscarLibros("recomendado", contadorPagina);
      break;
    case "fisico":
      buscarLibros("fisico", contadorPagina);
      break;
  }
});

buscarLibros("todos");
