<?php
// obtenim les dades enviades

$input = file_get_contents('php://input');
$usuariRespostes = json_decode($input, true);

//llegir rl fitxer 
$jsonData = file_get_contents('data.json');
$preguntes = json_decode($jsonData, true)['preguntes'];

// les variables per comptar preguntes totals i respostes correctes
$totalPreguntes = 10;
$respostesCorrectes = 0;

// comprovar les respostes de l'usuari amb les correctes
for ($i = 0; $i < $totalPreguntes; $i++) {
    // Comprova si la resposta seleccionada és igual a la resposta correcta
    if (isset($usuariRespostes[$i]) && $usuariRespostes[$i] == $preguntes[$i]['resposta_correcta']) {
        $respostesCorrectes++;
    }
}
// l'objecte resultat inclou el nombre total de preguntes i les respostes correctes
$resultat = [
    'totalPreguntes' => $totalPreguntes,
    'respostesCorrectes' => $respostesCorrectes
];
// retorna lel resultat en format JSON
echo json_encode($resultat);

?>