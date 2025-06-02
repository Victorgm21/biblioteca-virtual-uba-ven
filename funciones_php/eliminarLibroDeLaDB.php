<?php
function eliminarLibro($conn, $id)
{
  /**************************************
   * // VERIFICAR SI EL ID ESTÁ PRESENTE *
   **************************************/
  if (!isset($id)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "El campo 'id' no está presente."));
    return;
  }

  /************************************
   * // VERIFICAR SI EL ID ES VÁLIDO *
   ************************************/
  if (!is_numeric($id)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "El campo 'id' debe ser un número válido."));
    return;
  }

  /*********************************************
   * // OBTENER LOS NOMBRES DE LOS ARCHIVOS *
   *********************************************/
  $sql = "SELECT imagen, documento FROM libros WHERE id = ?";
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    http_response_code(500); // Error del servidor
    echo json_encode(array("error" => "Error al preparar la consulta: " . $conn->error));
    return;
  }

  // Declarar las variables antes de usarlas en bind_result
  $imagenName = "";
  $documentoName = "";

  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($imagenName, $documentoName); // Ahora las variables están declaradas
  $stmt->fetch();

  if ($stmt->num_rows == 0) {
    http_response_code(404); // No encontrado
    echo json_encode(array("error" => "No se encontró el libro con el ID proporcionado."));
    $stmt->close();
    return;
  }

  $stmt->close();

  /******************************************
   * // ELIMINAR LOS ARCHIVOS DEL SERVIDOR *
   ******************************************/
  $uploadDirectory = "C:/xampp/htdocs/biblioteca/uploads/"; // Asegúrate de que esta ruta sea correcta

  if (!empty($imagenName)) {
    $imagenPath = $uploadDirectory . $imagenName;
    if (file_exists($imagenPath)) {
      unlink($imagenPath);
    }
  }

  if (!empty($documentoName)) {
    $documentoPath = $uploadDirectory . $documentoName;
    if (file_exists($documentoPath)) {
      unlink($documentoPath);
    }
  }

  /******************************************
   * // ELIMINAR EL REGISTRO DE LA BASE DE DATOS *
   ******************************************/
  $sql = "DELETE FROM libros WHERE id = ?";
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    http_response_code(500); // Error del servidor
    echo json_encode(array("error" => "Error al preparar la consulta: " . $conn->error));
    return;
  }

  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    http_response_code(200); // OK
    echo json_encode(array("message" => "Libro eliminado correctamente."));
  } else {
    http_response_code(500); // Error del servidor
    echo json_encode(array("error" => "Error al eliminar el libro de la base de datos: " . $stmt->error));
  }

  $stmt->close();
}
?>