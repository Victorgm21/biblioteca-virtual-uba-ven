<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");


header("Access-Control-Allow-Origin: *"); // Permite cualquier origen
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos

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
    buscarLibros($conn);
    break;
  default:
    http_response_code(405);
    echo json_encode(array("error" => "Método no permitido"));
    break;
}

function buscarLibros($conn)
{
  // Verificar parámetros obligatorios (tipobusqueda debe estar definido)
  if (empty($_GET["tipobusqueda"] ?? null)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(["error" => "NO SE ENVIÓ EL PARÁMETRO TIPO DE BUSQUEDA"]);
    exit();
  }

  $busqueda = $_GET["busqueda"] ?? "";
  $tipoBusqueda = $_GET["tipobusqueda"];
  $terminoBusqueda = "%" . $busqueda . "%";

  // Inicializar SQL y parámetros
  $sql = "SELECT * FROM `libros` WHERE 1=1"; // 1=1 para facilitar concatenación de condiciones
  $params = [];
  $types = "";

  // Solo aplicar filtro de título/autor si la búsqueda NO está vacía
  if (!empty($busqueda)) {
    if ($tipoBusqueda == "autor") {
      $sql .= " AND autor LIKE ?";
    } else if ($tipoBusqueda == "titulo") {
      $sql .= " AND titulo LIKE ?";
    }
    $params[] = $terminoBusqueda;
    $types .= "s";
  }

  // Filtros adicionales (siempre aplican)
  if (!empty($_GET["categoria"] ?? null)) {
    $categoria = "%" . $_GET["categoria"] . "%";
    $sql .= " AND categoria LIKE ?";
    $params[] = $categoria;
    $types .= "s";
  }

  if (!empty($_GET["subcategoria"] ?? null)) {
    $subcategoria = "%" . $_GET["subcategoria"] . "%";
    $sql .= " AND subcategoria LIKE ?";
    $params[] = $subcategoria;
    $types .= "s";
  }

  // Filtro físico/digital
  if (!empty($_GET["fisico"] ?? null)) {
    if ($_GET["fisico"] == "si") {
      $sql .= " AND cantidad > 0";
    } else if ($_GET["fisico"] == "no") {
      $sql .= " AND digital = 1"; // Asumiendo que 'digital' es un booleano
    }
  }

  // Preparar consulta
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la consulta SQL: " . $conn->error]);
    exit();
  }

  // Vincular parámetros si existen
  if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $resultados = $result->fetch_all(MYSQLI_ASSOC);

  // Respuesta
  header('Content-Type: application/json');
  echo json_encode($resultados);
  $stmt->close();
}