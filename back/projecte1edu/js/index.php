<?php
session_start(); // iniciem la sessió

// carreguem les preguntes del json
$json = file_get_contents('data.json');
$dades = json_decode($json, true);

// si és la primera vegada que es carrega la pàgina
if (!isset($_SESSION['preguntes'])) {
    // seleccionem 10 preguntes aleatòries
    $_SESSION['preguntes'] = array_rand($dades['preguntes'], 10);
    $_SESSION['puntuacio'] = 0;
    $_SESSION['pregunta_actual'] = 0;
    $_SESSION['respostes_usuari'] = []; // guardar respostes de l'usuari
}

// obtenir la pregunta actual
$preguntaIndex = $_SESSION['pregunta_actual'];
$preguntaActual = $dades['preguntes'][$_SESSION['preguntes'][$preguntaIndex]];

// Processar la resposta si s'ha enviat el formulari
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respostaUsuari = $_POST['resposta'];
    $respostaCorrecta = $preguntaActual['resposta_correcta'];

    // Guardar la resposta de l'usuari a l'array de respostes
    $_SESSION['respostes_usuari'][] = $respostaUsuari;

    // Comprovar si la resposta és correcta
    if ($respostaUsuari == $respostaCorrecta) {
        $_SESSION['puntuacio']++;
        $feedback = "Correcte!";
    } else {
        $feedback = "Incorrecte! La resposta correcta és: " . $preguntaActual['respostes'][$respostaCorrecta];
    }

    // Avançar a la següent pregunta
    $_SESSION['pregunta_actual']++;

    // Si ja s'han respost les 10 preguntes, redirigir a la pàgina de resultats
    if ($_SESSION['pregunta_actual'] >= 10) {
        header("Location: resultat.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Pregunta <?= $_SESSION['pregunta_actual'] + 1 ?> de 10</h1>
        <h2 class="pregunta"><?= $preguntaActual['pregunta'] ?></h2>

        <form method="POST">
            <?php foreach ($preguntaActual['respostes'] as $index => $resposta): ?>
                <label>
                    <input type="radio" name="resposta" value="<?= $index ?>" required>
                    <?= $resposta ?>
                </label><br>
            <?php endforeach; ?>
            <button type="submit">Enviar resposta</button>
        </form>

        <?php if (isset($feedback)): ?>
            <p class="feedback"><?= $feedback ?></p>
        <?php endif; ?>

        <p class="puntuacio">Puntuació actual: <?= $_SESSION['puntuacio'] ?> / 10</p>
    </div>
</body>
</html>
