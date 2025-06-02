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
    buscarCategorias($conn);
    break;
  default:
    http_response_code(405);
    echo json_encode(array("error" => "Método no permitido"));
    break;
}

function buscarCategorias($conn)
{
  if (isset($_GET["subcategorias"])) {
    // Objeto que contendrá todas las subcategorías organizadas por categoría
    $resultadoFinal = array();

    // Consulta para subcategorías de psicología
    $sqlpsicologia = "SELECT subcategoria FROM `subcategorias_psicologia`";
    $subPsicologia = array();
    $stmt = $conn->prepare($sqlpsicologia);
    if ($stmt === false) {
      http_response_code(500);
      echo json_encode(array("error" => "Error al preparar la consulta de PSICOLOGIA"));
      return;
    }
    if ($stmt->execute()) {
      $resultado = $stmt->get_result();
      while ($fila = $resultado->fetch_assoc()) {
        $subPsicologia[] = $fila['subcategoria'];
      }
      $resultadoFinal['Psicología'] = $subPsicologia;
    }
    $stmt->close();

    // Consulta para subcategorías de contaduria
    $sqlContaduria = "SELECT subcategoria FROM `subcategorias_contaduria`";
    $subContaduria = array();
    $stmt = $conn->prepare($sqlContaduria);
    if ($stmt === false) {
      http_response_code(500);
      echo json_encode(array("error" => "Error al preparar la consulta de derecho"));
      return;
    }
    if ($stmt->execute()) {
      $resultado = $stmt->get_result();
      while ($fila = $resultado->fetch_assoc()) {
        $subContaduria[] = $fila['subcategoria'];
      }
      $resultadoFinal['Contaduría pública'] = $subContaduria;
    }
    $stmt->close();


    // Consulta para subcategorías de comunicacion social
    $sqlcomunicacion = "SELECT subcategoria FROM `subcategorias_comunicacionsocial`";
    $subComunicacion = array();
    $stmt = $conn->prepare($sqlcomunicacion);
    if ($stmt === false) {
      http_response_code(500);
      echo json_encode(array("error" => "Error al preparar la consulta de comunicación social"));
      return;
    }
    if ($stmt->execute()) {
      $resultado = $stmt->get_result();
      while ($fila = $resultado->fetch_assoc()) {
        $subComunicacion[] = $fila['subcategoria'];
      }
      $resultadoFinal['Comunicación social'] = $subComunicacion;
    }
    $stmt->close();

    // Consulta para subcategorías de derecho
    $sqlDerecho = "SELECT subcategoria FROM `subcategorias_derecho`";
    $subDerecho = array();
    $stmt = $conn->prepare($sqlDerecho);
    if ($stmt === false) {
      http_response_code(500);
      echo json_encode(array("error" => "Error al preparar la consulta de derecho"));
      return;
    }
    if ($stmt->execute()) {
      $resultado = $stmt->get_result();
      while ($fila = $resultado->fetch_assoc()) {
        $subDerecho[] = $fila['subcategoria'];
      }
      $resultadoFinal['Derecho'] = $subDerecho;
    }
    $stmt->close();

    // Consulta para subcategorías de administracion
    $sqlAdministracion = "SELECT subcategoria FROM `subcategorias_administracion`";
    $subAdministracion = array();
    $stmt = $conn->prepare($sqlAdministracion);
    if ($stmt === false) {
      http_response_code(500);
      echo json_encode(array("error" => "Error al preparar la consulta de derecho"));
      return;
    }
    if ($stmt->execute()) {
      $resultado = $stmt->get_result();
      while ($fila = $resultado->fetch_assoc()) {
        $subAdministracion[] = $fila['subcategoria'];
      }
      $resultadoFinal['Administración de empresas'] = $subAdministracion;
    }
    $stmt->close();

    // Consulta para subcategorías de ingeniería
    $sqlIngenieria = "SELECT subcategoria FROM `subcategorias_ingenieria`";
    $subIngenieria = array();
    $stmt = $conn->prepare($sqlIngenieria);
    if ($stmt === false) {
      http_response_code(500);
      echo json_encode(array("error" => "Error al preparar la consulta de ingeniería"));
      return;
    }
    if ($stmt->execute()) {
      $resultado = $stmt->get_result();
      while ($fila = $resultado->fetch_assoc()) {
        $subIngenieria[] = $fila['subcategoria'];
      }
      $resultadoFinal['Ingeniería'] = $subIngenieria;
    }
    $stmt->close();

    echo json_encode($resultadoFinal);
  } else {
    // Consulta original para categorías principales
    $sql = "SELECT categoria FROM `categorias`";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
      http_response_code(500);
      echo json_encode(array("error" => "Error al preparar la consulta SQL"));
      return;
    }
    if ($stmt->execute()) {
      $resultado = $stmt->get_result();
      $datos = array();
      while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
      }
      echo json_encode($datos);
    } else {
      http_response_code(500);
      echo json_encode(array("error" => "Error al ejecutar la consulta SQL"));
    }
    $stmt->close();
  }
}

?>