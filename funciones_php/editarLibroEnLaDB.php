<?php

function getRequestBody()
{
  // Verificar el método de la petición
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

  // Solo procesar cuerpo para POST, PUT, PATCH
  if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
    return [];
  }

  // Obtener el contenido tipo
  $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

  // Para JSON
  if (strpos($contentType, 'application/json') !== false) {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    return is_array($data) ? $data : [];
  }

  // Para formularios tradicionales
  if ($method === 'POST') {
    return $_POST;
  }

  // Para otros métodos (PUT, PATCH, DELETE) con formato x-www-form-urlencoded
  parse_str(file_get_contents('php://input'), $putData);
  return $putData;
}

function editarLibro($conn, $id)
{
  // Verificación de ID
  if (!isset($id) || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(["error" => "ID inválido o no proporcionado"]);
    return;
  }

  // Obtener datos
  $requestData = getRequestBody();

  // DEBUG: Ver datos recibidos
  error_log("Datos recibidos en editarLibro: " . print_r($requestData, true));

  // Validar campos obligatorios
  $camposObligatorios = ['titulo', 'autor', 'descripcion'];
  foreach ($camposObligatorios as $campo) {
    if (!isset($requestData[$campo]) || trim($requestData[$campo]) === '') {
      http_response_code(400);
      echo json_encode(["error" => "El campo '$campo' es requerido y no puede estar vacío."]);
      return;
    }
  }

  // Preparar datos
  $titulo = trim($requestData['titulo']);
  $autor = trim($requestData['autor']);
  $descripcion = trim($requestData['descripcion']);
  $recomendado = isset($requestData['recomendado']) ? $requestData['recomendado'] : 'no';
  $trimestre = isset($requestData['trimestre']) && $requestData['trimestre'] !== '' ? intval($requestData['trimestre']) : null;
  $isbn = isset($requestData['isbn']) && $requestData['isbn'] !== '' ? trim($requestData['isbn']) : null;
  $cantidad = isset($requestData['cantidad']) ? intval($requestData['cantidad']) : 0;
  $categoria = isset($requestData['categoria']) ? trim($requestData['categoria']) : 'Sin categoría';
  $subcategoria = isset($requestData['subcategoria']) ? trim($requestData['subcategoria']) : 'Sin subcategoría';

  // Validaciones adicionales
  if ($trimestre !== null && ($trimestre < 1 || $trimestre > 12)) {
    http_response_code(400);
    echo json_encode(["error" => "El trimestre debe estar entre 1 y 12"]);
    return;
  }

  if ($cantidad < 0) {
    http_response_code(400);
    echo json_encode(["error" => "La cantidad no puede ser negativa"]);
    return;
  }

  // Actualizar en BD
  $sql = "UPDATE libros SET 
            titulo = ?,
            autor = ?,
            descripcion = ?,
            recomendado = ?,
            trimestre = ?,
            isbn = ?,
            cantidad = ?,
            categoria = ?,
            subcategoria = ?
            WHERE id = ?";

  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
    return;
  }

  // Bind parameters (10 parámetros en total)
  $stmt->bind_param(
    "sssssisssi",  // Tipos: s=string, i=integer
    $titulo,
    $autor,
    $descripcion,
    $recomendado,
    $trimestre,
    $isbn,
    $cantidad,
    $categoria,
    $subcategoria,
    $id
  );

  if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Error al actualizar: " . $stmt->error]);
    return;
  }

  if ($stmt->affected_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "No se encontró el libro o los datos son idénticos"]);
    return;
  }

  http_response_code(200);
  echo json_encode([
    "success" => true,
    "message" => "Libro actualizado correctamente",
    "data" => [
      "id" => $id,
      "titulo" => $titulo,
      "autor" => $autor
    ]
  ]);

  $stmt->close();
}