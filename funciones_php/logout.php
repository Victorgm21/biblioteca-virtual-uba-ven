<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

session_start();

// Destruir la sesión
session_unset();
session_destroy();

echo json_encode(array("mensaje" => "Sesión cerrada correctamente"));