<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start();

$host = "localhost";
$usuario = "root";
$password = "";
$bdd = "biblioteca_digital";

$conn = new mysqli($host, $usuario, $password, $bdd);

if ($conn->connect_error) {
  die("CONEXION NO ESTABLECIDA ERROR: " . $conn->connect_error);
}

// Datos de usuario válidos (en producción usa una tabla en la BD)
$usuariosValidos = [
  'admin' => password_hash('admin123', PASSWORD_DEFAULT) // Contraseña: admin123
];

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === "POST") {
  $datos = json_decode(file_get_contents("php://input"), true);

  if (!isset($datos['usuario']) || !isset($datos['password'])) {
    http_response_code(400);
    echo json_encode(array("error" => "Usuario y contraseña requeridos"));
    exit();
  }

  $usuario = $datos['usuario'];
  $password = $datos['password'];

  // Verificar si el usuario existe y la contraseña es correcta
  if (isset($usuariosValidos[$usuario]) && password_verify($password, $usuariosValidos[$usuario])) {
    $_SESSION['usuario_autenticado'] = true;
    $_SESSION['usuario'] = $usuario;

    echo json_encode(array("mensaje" => "Inicio de sesión exitoso", "usuario" => $usuario));
  } else {
    http_response_code(401);
    echo json_encode(array("error" => "Credenciales inválidas"));
  }
} else {
  http_response_code(405);
  echo json_encode(array("error" => "Método no permitido"));
}