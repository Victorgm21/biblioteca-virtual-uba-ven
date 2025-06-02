<?php
session_start();

function verificarAutenticacion()
{
  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(array("error" => "Acceso no autorizado. Debes iniciar sesión."));
    exit();
  }
}