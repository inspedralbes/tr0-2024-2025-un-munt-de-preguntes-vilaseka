<?php
$numPreguntes = (int) $_GET['num'];

// llegir el fitxer data.josn
$jsonData = file_get_contents('data.json');
$preguntes = json_decode($jsonData, true)['preguntes']; 

// barrejem les preguntes i seleccionem les primeres 10 (numPreguntes)
shuffle($preguntes);
$preguntesSeleccionades = array_slice($preguntes, 0, $numPreguntes);

// eleimen el camp (resposta_correcte) de cada preguntes
foreach ($preguntesSeleccionades as &$pregunta) {
    unset($pregunta['resposta_correcta']);  // Elimina la resposta correcta
}

// retorna les preguntes seleccionades en format JSON
echo json_encode($preguntesSeleccionades);

?>