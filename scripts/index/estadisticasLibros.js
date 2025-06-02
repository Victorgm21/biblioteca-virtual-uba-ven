import { rutaRaiz } from "../../rutas.js";

fetch(rutaRaiz + "/api.php?contador")
  .then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  })
  .then((data) => {
    console.log(data); // Imprime los datos en la consola
    const cantidadLibros = document.querySelector(".libros-cantidad");
    cantidadLibros.innerHTML = data.numeroDeLibros;
    const cantidadLibrosDigitales = document.querySelector(
      ".libros-digitales-cantidad"
    );
    cantidadLibrosDigitales.innerHTML = data.numeroDeLibrosDigitales;
  })
  .catch((error) => {
    console.error("There has been a problem with your fetch operation:", error);
  });
