<?php

function select($conn, $tipoBusqueda, $searchById = false)
{
  $id = null;
  $sql = "";
  $params = []; // Array para almacenar los parámetros de la consulta preparada

  // Determinar la consulta SQL según el tipo de búsqueda
  if ($searchById) {
    $id = $_GET['id'] ?? null;
    if ($id !== null && is_numeric($id)) { // Validar que el ID sea un número
      $sql = "SELECT * FROM `libros` WHERE `id` = ?";
      $params[] = $id; // Agregar el ID como parámetro
    } else {
      http_response_code(400); // Solicitud incorrecta
      echo json_encode(array("error" => "ID no válido"));
      return;
    }
  } else {
    $offset = $_GET['offset'] ?? null;
    if ($offset !== null && is_numeric($offset) && $searchById == false) { // Validar que el ID sea un número
      $offset = $offset * 9;
      $params[] = $offset;
    } else {
      $offset = 0;
      $params[] = $offset;
    }
    switch ($tipoBusqueda) {
      case "recomendado":
        $sql = "SELECT `id`, `titulo`, `autor`, `descripcion`, `imagen` 
                        FROM `libros` 
                        WHERE recomendado = 'si' 
                        ORDER BY `id` DESC 
                        LIMIT 9
                        OFFSET ?;";
        break;
      case "fisico":
        $sql = "SELECT `id`, `titulo`, `autor`, `descripcion`, `imagen` 
                        FROM `libros` 
                        WHERE cantidad > 0 
                        ORDER BY `id` DESC 
                        LIMIT 9
                        OFFSET ?
                        ;";
        break;
      default:
        $sql = "SELECT `id`, `titulo`, `autor`, `descripcion`, `imagen` 
                        FROM `libros` 
                        ORDER BY `id` DESC 
                        LIMIT 9
                        OFFSET ?
                        ;";
        break;
    }
  }

  // Preparar y ejecutar la consulta
  if ($sql) {
    $stmt = $conn->prepare($sql); // Preparar la consulta
    if ($stmt === false) {
      http_response_code(500); // Error del servidor
      echo json_encode(array("error" => "Error al preparar la consulta SQL"));
      return;
    }

    // Vincular parámetros si es necesario
    if (!empty($params)) {
      $stmt->bind_param("i", $params[0]); // "i" indica que el parámetro es un entero
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
      $resultado = $stmt->get_result(); // Obtener el resultado
      $datos = array();
      while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
      }
      echo json_encode($datos); // Devolver los datos en formato JSON
    } else {
      http_response_code(500); // Error del servidor
      echo json_encode(array("error" => "Error al ejecutar la consulta SQL"));
    }

    // Cerrar la declaración
    $stmt->close();
  } else {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(array("error" => "Consulta SQL no válida"));
  }
}

?>