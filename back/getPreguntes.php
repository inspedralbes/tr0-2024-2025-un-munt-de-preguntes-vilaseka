<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
// nombre de preguntes que voler obtenir

$numPreguntes = isset($_GET['num']) ? (int) $_GET['num'] : 0;

// llegir el fitxer data.josn
$jsonData = file_get_contents('../back/data.json');
$preguntes = json_decode($jsonData, true)['preguntes'];

// barrejem les preguntes i seleccionem les primeres (numPreguntes)
shuffle($preguntes  );
$preguntesSeleccionades = array_slice($preguntes, 0, $numPreguntes);

// eleimen el camp (resposta_correcte) de cada preguntes
foreach ($preguntesSeleccionades as &$pregunta) {
    unset($pregunta['resposta_correcta']);  // Elimina la resposta correcta
}

// retorna les preguntes seleccionades en format JSON
echo json_encode($preguntesSeleccionades);

?>