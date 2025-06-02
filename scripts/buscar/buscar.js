import { rutaRaiz } from "../../rutas.js";
const librosContainer = document.querySelector(".libros-grid");

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

document
  .getElementById("search-book-form")
  .addEventListener("submit", function (e) {
    e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional

    // Obtener los valores del formulario
    const busqueda = document.getElementById("text-search").value;
    const tipoBusqueda = document.getElementById("tipo-busqueda").value;
    const categoria = document.getElementById("categorias").value;
    const subcategoria = document.getElementById("subcategorias").value;
    const fisico = document.getElementById("fisico-select").value;

    // Construir la URL con los parámetros de búsqueda
    let url = rutaRaiz + "/funciones_php/busquedaAvanzada.php?";
    url += `tipobusqueda=${encodeURIComponent(tipoBusqueda)}`;
    url += `&busqueda=${encodeURIComponent(busqueda)}`;

    // Solo añadir categoría si no es "sin categoría"
    if (categoria !== "sin categoría") {
      url += `&categoria=${encodeURIComponent(categoria)}`;
    }

    // Solo añadir subcategoría si no es "sin subcategoría"
    if (subcategoria !== "sin subcategoría") {
      url += `&subcategoria=${encodeURIComponent(subcategoria)}`;
    }

    // Añadir parámetro físico si no es "no" (que sería el valor por defecto)
    if (fisico !== "no") {
      url += `&fisico=${encodeURIComponent(fisico)}`;
    }

    console.log("URL de búsqueda:", url); // Mostrar URL en consola para depuración

    // Hacer la petición fetch a la API
    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la respuesta de la red");
        }
        return response.json();
      })
      .then((data) => {
        librosContainer.innerHTML = "";
        if (Array.isArray(data) && data.length === 0) {
          alert("No hay resultados");
        }
        insertarLibrosEnElGrid(data);
        console.log("Resultados de búsqueda:", data); // Mostrar resultados en consola
      })
      .catch((error) => {
        console.error("Error al realizar la búsqueda:", error);
      });
  });
