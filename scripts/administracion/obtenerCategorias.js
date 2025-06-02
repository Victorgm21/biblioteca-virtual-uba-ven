import { rutaRaiz } from "../../rutas.js";

const categoriaSelect = document.getElementById("categorias");
const subcategoriaSelect = document.getElementById("subcategorias");

let subcategoriasJSON;

async function obtenerCategorias() {
  try {
    const url = `${rutaRaiz}/funciones_php/buscarCategorias.php`;
    console.log(url);

    const response = await fetch(url, {
      headers: {
        Accept: "application/json",
      },
    });

    // Verificar si la respuesta es exitosa
    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(
        `Error HTTP! estado: ${response.status}, respuesta: ${errorText}`
      );
    }

    // Verificar el tipo de contenido
    const contentType = response.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
      const text = await response.text();
      throw new Error(
        `Se esperaba JSON pero se recibió: ${contentType}, contenido: ${text.substring(
          0,
          100
        )}...`
      );
    }

    // Parsear la respuesta como JSON
    const categorias = await response.json();

    // Validar la estructura de los datos
    if (!Array.isArray(categorias)) {
      throw new Error("La respuesta no es un array válido");
    }

    // Limpiar y llenar las opciones
    const selectorCategoria = document.getElementById("categorias");
    categorias.forEach((categoria) => {
      const nuevaCategoria = `<option value="${categoria.categoria}">${categoria.categoria}</option>`;
      selectorCategoria.innerHTML += nuevaCategoria;
    });
  } catch (error) {
    console.error("Error al obtener tipos de productos:", error);
    // Mostrar mensaje al usuario
    alert("Error al cargar las categorías de los libros");
  }
}

async function obtenerSubcategorias() {
  try {
    const url = `${rutaRaiz}/funciones_php/buscarCategorias.php?subcategorias`;
    console.log(url);
    const response = await fetch(url, {
      headers: {
        Accept: "application/json",
      },
    });

    // Verificar si la respuesta es exitosa
    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(
        `Error HTTP! estado: ${response.status}, respuesta: ${errorText}`
      );
    }

    // Verificar el tipo de contenido
    const contentType = response.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
      const text = await response.text();
      throw new Error(
        `Se esperaba JSON pero se recibió: ${contentType}, contenido: ${text.substring(
          0,
          100
        )}...`
      );
    }

    // Parsear la respuesta como JSON
    const subcategorias = await response.json();
    return subcategorias;
  } catch (error) {
    console.error(
      "Error al cargar las subcategorias de productos. Por favor recarga la página.: ",
      error
    );
    // Mostrar mensaje al usuario
    alert(
      "Error al cargar cargar las subcategorias. Por favor recarga la página."
    );
  }
}

function actualizarSubcategoria(arreglo) {
  subcategoriaSelect.innerHTML = `           
    <option value="sin subcategoría" selected>
      Sin subcategoría
    </option>`;
  arreglo.forEach((subcategoria) => {
    const nuevaOpcion = `
    <option value="${subcategoria}">
      ${subcategoria}
    </option>`;
    subcategoriaSelect.innerHTML += nuevaOpcion;
  });
}

// Evento change para cargar subcategorías al seleccionar categoría
categoriaSelect.addEventListener("change", () => {
  const categoriaId = categoriaSelect.value;

  actualizarSubcategoria(subcategoriasJSON[categoriaId]);
});

obtenerCategorias();
subcategoriasJSON = await obtenerSubcategorias();
