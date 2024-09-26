<?php
// establim connexio amb la base de dades
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "UMDP";

// creem la connexio
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection (w3schools) verificaio
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//llegim el fitxer json
$json = file_get_contents('js/data.json');
$json = json_decode($json, true);

//BUCLE PER INSERIR DADES
foreach ($data['preguntes'] as $row) {
    $id = $row['id'];
    $pregunta = $row['pregunta'];
    $r1 = $row['respostes'][0];
    $r2 = $row['respostes'][1];
    $r3 = $row['respostes'][2];
    $r4 = $row['respostes'][3];
    $rcorrecte = $row['resposta_correcta'];

    $sql = "INSERT INTO preguntes_existents ('id','pregunta','r1','r2','r3','r4','rcorrecte') VALUES ('$id','$pregunta','$r1','$r2','$r3','$r4','$rcorrecte');";
}

//tanquem la connexio
$conn->close();
?>