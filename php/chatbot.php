<?php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "respuesta" => "Acceso no permitido."
    ]);
    exit;
}

$mensaje = '';

$rawBody = file_get_contents('php://input');

if (!empty($rawBody)) {
    $datosJson = json_decode($rawBody, true);

    if (is_array($datosJson)) {
        $mensaje = trim((string)($datosJson['mensaje'] ?? $datosJson['pregunta'] ?? ''));
    }
}

if ($mensaje === '' && isset($_POST['mensaje'])) {
    $mensaje = trim((string)$_POST['mensaje']);
}

if ($mensaje === '' && isset($_POST['pregunta'])) {
    $mensaje = trim((string)$_POST['pregunta']);
}

if ($mensaje == '') {
    echo json_encode([
        "success" => false,
        "respuesta" => "Escribe una pregunta."
    ]);
    exit;
}

// Convertir a minúsculas
$mensaje = mb_strtolower($mensaje, 'UTF-8');

// Eliminar tildes
$mensaje = strtr($mensaje, [
    'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
    'Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u',
    'ñ'=>'n','Ñ'=>'n'
]);

// Eliminar signos
$mensaje = preg_replace('/[^a-z0-9\s]/u', '', $mensaje);

// Consultar respuestas
$sql = "SELECT * FROM preguntas WHERE activa = 1";

$resultado = $conexion->query($sql);

if (!$resultado) {

    echo json_encode([
        "success" => false,
        "respuesta" => "Error SQL: " . $conexion->error
    ]);

    exit;
}

$mejorRespuesta = "";
$mayorCoincidencia = 0;

while ($fila = $resultado->fetch_assoc()) {

    $palabras = explode(",", strtolower($fila['palabras_clave']));

    $coincidencias = 0;

    foreach ($palabras as $palabra) {

        $palabra = trim($palabra);

        if ($palabra == '') {
            continue;
        }

        // Compatible con PHP 7 y 8
        if (strpos($mensaje, $palabra) !== false) {
            $coincidencias++;
        }

    }

    if ($coincidencias > $mayorCoincidencia) {

        $mayorCoincidencia = $coincidencias;
        $mejorRespuesta = $fila['respuesta'];

    }

}

// Si encontró coincidencias
if ($mejorRespuesta != "") {

    echo json_encode([
        "success" => true,
        "respuesta" => $mejorRespuesta
    ]);

} else {

    echo json_encode([
        "success" => true,
        "respuesta" => "😔 Lo siento, todavía no conozco la respuesta a esa pregunta.<br><br>
        Puedes preguntarme sobre:<br><br>
        📚 Noticias<br>
        🏫 Nosotros<br>
        📸 Galería<br>
        🎓 Proyectos<br>
        📞 Contacto"
    ]);

}

$conexion->close();
?>