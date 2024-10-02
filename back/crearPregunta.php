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

//aqui hem d'obtenir les dades del post
$pregunta = $_POST['pregunta'];
$r1 = $_POST['r1'];
$r2 = $_POST['r2'];
$r3 = $_POST['r3'];
$r4 = $_POST['r4'];
$rcorrecte = $_POST['rcorrecte'];

//ara hem de fer la query per inserir la pregunta

$sql = "INSERT INTO preguntes_existents (pregunta, r1, r2, r3, r4 , rcorrecte) VALUES (?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $pregunta, $r1, $r2, $r3, $r4, $rcorrecte);
$stmt->execute();

//comprovació
if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Pregunta afegida correctament!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error']);
}

$stmt->close();
$conn->close();
?>