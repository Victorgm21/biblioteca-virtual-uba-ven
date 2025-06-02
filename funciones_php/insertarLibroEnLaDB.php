<?php

function generarNombreUnico($nombreOriginal)
{
  // Obtener la hora actual en formato timestamp
  $timestamp = time();

  // Extraer la extensión del archivo
  $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);

  // Crear un nombre único: timestamp_nombreoriginal.extension
  $nombreUnico = $timestamp . "_" . basename($nombreOriginal, "." . $extension) . "." . $extension;

  return $nombreUnico;
}

function insertarLibro($conn)
{
  /*************************************************
   * // DIRECTORIO DONDE SE GUARDARÁN LOS ARCHIVOS *
   *************************************************/
  $uploadDirectory = "C:/xampp/htdocs/biblioteca/uploads/"; // Asegúrate de que esta ruta sea correcta

  /*************************************************************
   * // VERIFICA SI LA CARPETA DE SUBIDA EXISTE, SI NO, CRÉALA *
   *************************************************************/
  if (!file_exists($uploadDirectory)) {
    if (!mkdir($uploadDirectory, 0777, true)) {
      http_response_code(500); // Error del servidor
      die(json_encode(array("error" => "No se encuentra la carpeta donde se guardan los archivos")));
    }
  }

  /***************************************************
   * // VERIFICA SI SE HA SUBIDO LA IMAGEN DEL LIBRO *
   ***************************************************/

  if (!isset($_FILES["imagen"])) {
    http_response_code(400); // Error de cliente
    echo json_encode(array("error" => "No se esta enviando una imagen del libro."));
    return;
  }

  // Verificar que todos los campos estén presentes

  /*
  if (!isset($_POST['titulo'], $_POST["autor"], $_POST["descripcion"], $_POST["carrera"], $_POST["trimestre"], $_POST["isbn"], $_POST["cantidad"])) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Faltan campos obligatorios en la solicitud."));
    return;
  }
*/

  /**************************************
   * // VERIFICAR SI SE ENVIO EL TITULO *
   **************************************/

  if (!isset($_POST['titulo'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'titulo' no esta siendo enviado al servidor."));
    return;
  }

  /*************************************
   * // VERIFICAR SI SE ENVIO EL AUTOR *
   *************************************/

  if (!isset($_POST['autor'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'autor' no esta siendo enviado al servidor"));
    return;
  }

  /*******************************************
   * // VERIFICAR SI SE ENVIO LA DESCRIPCION *
   *******************************************/

  if (!isset($_POST['descripcion'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'descripcion' no esta siendo enviado al servidor"));
    return;
  }


  /*******************************************
   * // VERIFICAR SI SE ENVIO LA CATEGORIA *
   *******************************************/

  if (!isset($_POST['categoria'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'categoria' no esta siendo enviado al servidor"));
    return;
  }

  if (!isset($_POST['subcategoria'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'subcategoria' no esta siendo enviado al servidor"));
    return;
  }

  /**********************************************************
   * // VERIFICAR SI SE ENVIO RECOMENDADO (POR EL PROFESOR) *
   **********************************************************/

  if (!isset($_POST['recomendado'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'recomendado' no esta siendo enviado al servidor"));
    return;
  }



  /*****************************************
   * // VERIFICAR SI SE ENVIO EL TRIMESTRE *
   *****************************************/

  if (!isset($_POST['trimestre'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'trimestre' no esta siendo enviado al servidor"));
    return;
  }

  /************************************
   * // VERIFICAR SI SE ENVIO EL ISBN *
   ************************************/

  if (!isset($_POST['isbn'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'isbn' no esta siendo enviado al servidor"));
    return;
  }


  /******************************************************
   * // VERIFICAR SI SE ENVIO LA CANTIDAD DE EJEMPLARES *
   ******************************************************/

  if (!isset($_POST['cantidad'])) {
    http_response_code(400);
    echo json_encode(array("error" => "El campo 'cantidad' no esta siendo enviado al servidor"));
    return;
  }

  // Verificar que los campos no estén vacíos

  /*
  if (empty($_POST['titulo']) || empty($_POST['autor']) || empty($_POST['descripcion']) || empty($_POST['carrera']) || empty($_POST['trimestre']) || empty($_POST['isbn']) || empty($_POST['cantidad'])) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Dejo campos de información acerca del libro vacíos."));
    return;
  }
    */


  /*************************************
   * // VERIFICAR SI TITULO ESTA VACIO *
   *************************************/

  if (empty(trim($_POST['titulo']))) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Título vacío"));
    return;
  }

  /************************************
   * // VERIFICAR SI AUTOR ESTA VACIO *
   ************************************/

  if (empty(trim($_POST['autor']))) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "autor vacío."));
    return;
  }

  /******************************************
   * // VERIFICAR SI DESCRIPCION ESTA VACIO *
   ******************************************/

  if (empty(trim($_POST['descripcion']))) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "descripción vacío."));
    return;
  }

  /******************************************
   * // VERIFICAR SI RECOMENDADO ESTA VACIO *
   ******************************************/

  if (empty(trim($_POST['descripcion']))) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "descripción vacío."));
    return;
  }


  /*********************************************
   * // DATOS A INTRODUCIR EN LA BASE DE DATOS *
   *********************************************/

  $titulo = $conn->real_escape_string($_POST['titulo']);
  $autor = $conn->real_escape_string($_POST['autor']);
  $descripcion = $conn->real_escape_string($_POST['descripcion']);
  $recomendado = $conn->real_escape_string($_POST['recomendado']);
  if ($recomendado != "si" && $recomendado != "no") {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Recomendado solo puede ser si o no."));
    return;
  }
  $trimestre = $conn->real_escape_string($_POST['trimestre']);
  $isbn = $conn->real_escape_string($_POST['isbn']);

  $categoria = $conn->real_escape_string($_POST['categoria']);
  $subcategoria = $conn->real_escape_string($_POST['subcategoria']);

  /***********************************************
   * // OBTENER Y VALIDAR EL VALOR DE 'CANTIDAD' *
   ***********************************************/
  $cantidad = trim($_POST['cantidad']);

  /****************************************************************
   * // VERIFICAR SI EL VALOR ESTÁ VACÍO O NO ES UN NÚMERO VÁLIDO *
   ****************************************************************/

  if ($cantidad === "" || !is_numeric($cantidad)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "El campo 'cantidad' debe ser un número válido."));
    return;
  }

  /*************************
   * // CONVERTIR A ENTERO *
   *************************/
  $cantidad = intval($cantidad);

  /******************************************************************
   * // VERIFICAR QUE LA CANTIDAD NO SEA NEGATIVA (SI ES NECESARIO) *
   ******************************************************************/

  if ($cantidad < 0) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "El campo 'cantidad' no puede ser negativo."));
    return;
  }

  /*************************
   * // PROCESAR LA IMAGEN *
   *************************/

  $imagenName = generarNombreUnico($_FILES["imagen"]["name"]);
  $imagenPath = $uploadDirectory . $imagenName;

  /*************************************************
   * // VALIDAR EL TIPO DE ARCHIVO (SOLO IMÁGENES) *
   *************************************************/

  $allowedImageTypes = ["image/jpeg", "image/png", "image/webp"];
  if (!in_array($_FILES["imagen"]["type"], $allowedImageTypes)) {
    http_response_code(400); // Error del cliente
    die(json_encode(array("error" => "Tipo de archivo no permitido para la imagen.")));
  }

  /************************************
   * // VALIDAR EL TAMAÑO DEL ARCHIVO *
   ************************************/

  $maxFileSize = 1000 * 1024 * 1024; // 50 MB
  if ($_FILES["imagen"]["size"] > $maxFileSize) {
    http_response_code(400); // Error del cliente
    die(json_encode(array("error" => "La imagen es demasiado grande.")));
  }

  /**********************************************
   * // MUEVE LA IMAGEN A LA CARPETA DE DESTINO *
   **********************************************/

  if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagenPath)) {
    http_response_code(500); // Error del servidor
    die(json_encode(array("error" => "No se pudo mover la imagen a la carpeta de destino.")));
  }

  /*******************************************************
   * // SI SE ENVIA DOCUMENTO PDF ENTONCES LO PROCESAMOS *
   *******************************************************/

  $documentoName = "";
  if (isset($_FILES["documento"])) {

    /**********************
     * // PROCESAR EL PDF *
     **********************/

    $documentoName = generarNombreUnico($_FILES["documento"]["name"]);
    $documentoPath = $uploadDirectory . $documentoName;

    /********************************************
     * // VALIDAR EL TIPO DE ARCHIVO (SOLO PDF) *
     ********************************************/

    $allowedPDFTypes = ["application/pdf"];
    if (!in_array($_FILES["documento"]["type"], $allowedPDFTypes)) {
      http_response_code(400); // Error del cliente
      die(json_encode(array("error" => "Tipo de archivo no permitido para el PDF.")));
    }

    /************************************
     * // VALIDAR EL TAMAÑO DEL ARCHIVO *
     ************************************/

    if ($_FILES["documento"]["size"] > $maxFileSize) {
      http_response_code(400); // Error del cliente
      die(json_encode(array("error" => "El PDF es demasiado grande.")));
    }

    /*******************************************
     * // MUEVE EL PDF A LA CARPETA DE DESTINO *
     *******************************************/

    if (!move_uploaded_file($_FILES["documento"]["tmp_name"], $documentoPath)) {
      http_response_code(500); // Error del servidor
      die(json_encode(array("error" => "No se pudo mover el PDF a la carpeta de destino.")));
    }
  }



  /*********************************************************************************
   * // PREPARAMOS EL NOMBRE DE LA IMAGEN Y EL DOCUMENTO PARA GUARDARLOS EN LA BDD *
   *********************************************************************************/

  $imagenName = $conn->real_escape_string($imagenName);
  $documentoName = $conn->real_escape_string($documentoName);


  /***********************************
   * // INSERTAR EN LA BASE DE DATOS *
   ***********************************/

  $sql = "INSERT INTO `libros` (`id`, `titulo`, `autor`, `descripcion`, `cantidad`, `categoria` , `subcategoria`, `recomendado`, `trimestre`, `isbn`, `imagen`, `documento`) 
  VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  /***************************
   * // PREPARAR LA CONSULTA *
   ***************************/

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    http_response_code(500); // Error del servidor
    echo json_encode(array("error" => "Error al preparar la consulta: " . $conn->error));
    return;
  }

  /*****************************
   * // BIND DE LOS PARÁMETROS *
   *****************************/

  $stmt->bind_param(
    "sssisssssss", // Tipos de datos: s = string, i = integer
    $titulo,
    $autor,
    $descripcion,
    $cantidad,
    $categoria,
    $subcategoria,
    $recomendado,
    $trimestre,
    $isbn,
    $imagenName,
    $documentoName
  );

  /***************************
   * // EJECUTAR LA CONSULTA *
   ***************************/

  if ($stmt->execute()) {
    http_response_code(201); // Creado con éxito
    echo json_encode(array("message" => "Libro insertado correctamente."));
  } else {
    http_response_code(500); // Error del servidor
    echo json_encode(array("error" => "Error al insertar en la base de datos: " . $stmt->error));
  }

  /*************************
   * // CERRAR LA CONSULTA *
   *************************/
  $stmt->close();
}