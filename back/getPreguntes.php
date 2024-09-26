<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
// nombre de preguntes que voler obtenir

$numPreguntes = isset($_GET['num']) ? (int) $_GET['num'] : 0;

// llegir el fitxer data.josn
$jsonData = file_get_contents('back/data.json');
$preguntes = json_decode($jsonData, true);

// barrejem les preguntes i seleccionem les primeres (numPreguntes)
shuffle($preguntes);
$preguntesSeleccionades = array_slice($preguntes, 0, $numPreguntes);

// eleimen el camp (correctindex) de cada preguntes
foreach ($preguntesSeleccionades as &$pregunta) {
    unset($pregunta['correctIndex']);  // Elimina la resposta correcta
}

// retorna les preguntes seleccionades en format JSON
echo json_encode($preguntesSeleccionades);

?>