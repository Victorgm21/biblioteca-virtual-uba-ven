const registrarButton = document.getElementById("registrar-button");
const modificarButton = document.getElementById("modificar-button");
const eliminarButton = document.getElementById("eliminar-button");

registrarButton.addEventListener("click", () => {
  window.location.href = "./insert.html";
});

modificarButton.addEventListener("click", () => {
  window.location.href = "./edit.html";
});

eliminarButton.addEventListener("click", () => {
  window.location.href = "./delete.html";
});
