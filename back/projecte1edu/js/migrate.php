//legir json, conectarnos base de dades, fer bucle del json i per cada pregunta anar insertant.

<?php
// establim connexio amb la base de dades
$servername = "localhost";
$username = "edu";
$password = "2024";

// creem la connexio
$conn = new mysqli($servername, $username, $password);

// Check connection (w3schools) verificaio
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//llegim el fitxer json
$json = file_get_contents('js/data.json');
$json = json_decode($json, true);

foreach ($data as $row) {
    $id = $row['id'];
    $pregunta = $row['pregunta'];
    $r1 = $row['r1'];
    $r2 = $row['r2'];
    $r3 = $row['r3'];
    $r4 = $row['r4'];
    $rcorrecte = $row['rcorrecte'];
    $sql = "INSERT INTO preguntes_existents ('id','pregunta','r1','r2','r3','r4','rcorrecta') VALUES ('$id','$pregunta','$r1','$r2','$r3','$r4','$rcorrecta');";
    mysqli_query($sql);

    $result = $conn->query($sql);
}
?>