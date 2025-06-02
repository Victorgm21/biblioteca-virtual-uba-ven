<?php


require_once './funciones_php/eliminarLibroDeLaDB.php';
require_once './funciones_php/insertarLibroEnLaDB.php';
require_once './funciones_php/buscarEnLaDB.php';
require_once './funciones_php/editarLibroEnLaDB.php';
require_once './funciones_php/auth-middleware.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");


header("Access-Control-Allow-Origin: *"); // Permite cualquier origen
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");


$host = "localhost";
$usuario = "root";
$password = "";
$bdd = "biblioteca_digital";

$conn = new mysqli($host, $usuario, $password, $bdd);

if ($conn->connect_error) {
  http_response_code(500); // Error del servidor
  die(json_encode(array("error" => "CONEXION NO ESTABLECIDA ERROR: " . $conn->connect_error)));
}

header("Content-Type: application/json");

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
  case "GET":
    if (isset($_GET["id"])) {
      select($conn, null, true);
    } else if (isset($_GET["tipo"])) {
      select($conn, $_GET["tipo"]);
    } else if (isset($_GET["contador"])) {
      buscarContadores($conn);
    } else {
      select($conn, "todos");
    }
    break;
  case "POST":
    verificarAutenticacion();
    insertarLibro($conn);
    break;
  case "DELETE":
    verificarAutenticacion();
    eliminarLibro($conn, $_GET["id"]);
    break;
  case "PUT":
    verificarAutenticacion();
    // Obtener el ID del libro a editar
    if (isset($_GET["id"])) {
      $idLibro = $_GET["id"];
      editarLibro($conn, $idLibro);
    } else {
      http_response_code(400); // Bad Request
      echo json_encode(array("error" => "ID del libro no proporcionado."));
    }
    break;
  default:
    http_response_code(405); // Método no permitido
    echo json_encode(array("error" => "Método no permitido"));
    break;
}

function buscarContadores($conn)
{
  $resultadoFinal = array();
  $sqlNumeroDeLibros = "SELECT COUNT(*) AS contador FROM `libros`;";
  $numeroDeLibros = "";
  $stmt = $conn->prepare($sqlNumeroDeLibros);
  if ($stmt === false) {
    http_response_code(500);
    echo json_encode(array("error" => "Error al preparar la consulta de numero de libros"));
    return;
  }
  if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    while ($fila = $resultado->fetch_assoc()) {
      $numeroDeLibros = $fila['contador'];
    }
    $resultadoFinal['numeroDeLibros'] = $numeroDeLibros;
  }
  $sqlNumeroDeLibrosDigitales = "SELECT COUNT(*) AS contador FROM `libros` WHERE cantidad < 1;";
  $numeroDeLibrosDigitales = "";
  $stmt = $conn->prepare($sqlNumeroDeLibrosDigitales);
  if ($stmt === false) {
    http_response_code(500);
    echo json_encode(array("error" => "Error al preparar la consulta de numero de libros"));
    return;
  }
  if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    while ($fila = $resultado->fetch_assoc()) {
      $numeroDeLibrosDigitales = $fila['contador'];
    }
    $resultadoFinal['numeroDeLibrosDigitales'] = $numeroDeLibrosDigitales;
  }
  http_response_code(200);
  echo json_encode($resultadoFinal);
}