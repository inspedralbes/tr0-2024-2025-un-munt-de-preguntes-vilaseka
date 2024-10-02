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

//obtenir dades per POST
$id = $_POST['id'];
$pregunta = $_POST['pregunta'];
$r1 = $_POST['r1'];
$r2 = $_POST['r2'];
$r3 = $_POST['r3'];
$r4 = $_POST['r4'];
$rcorrecte = $_POST['rcorrecte'];

//actualitzem la pregunta
$sql = "UPDATE preguntes_existents SET pregunta=?, r1=?,r2=?,r3=?,r4=?,rcorrecte=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt = $stmt->bind_param("ssssssi", $pregunta, $r1, $r2, $r3, $r4, $rcorrecte, $id);
$stmt->execute();

//comprovació
if ($stmt->affected_rows > 0) {
    echo json_encode(['succes' => true, 'message' => 'Pregunta actualitzada!']);
} else {
    echo json_encode(['succes' => false, 'message' => 'Error al actualitzar la pregunta']);
}

$stmt->close();
$conn->close();

?>