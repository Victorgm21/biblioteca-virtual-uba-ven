document.getElementById("logoutBtn").addEventListener("click", function () {
  fetch("localhost:8080/ferreguayana/php/logout.php", {
    method: "POST",
  }).then(() => {
    localStorage.removeItem("usuario");
    window.location.href = "./login.html";
  });
});
