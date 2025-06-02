document.addEventListener("DOMContentLoaded", function () {
  if (!localStorage.getItem("usuario")) {
    window.location.href = "../login.html";
  }
});
