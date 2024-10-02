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

//obtenim només la id desde el POST
$id = $_POST['id'];

//eliminem la pregunta

$sql = "DELETE FROM preguntes_existents WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

//comporvació 
if ($stmt->affected_rows > 0) {
    echo json_encode(['succes' => true, 'message' => 'Pregunta eliminada']);
} else {
    echo json_encode(['succes' => false, 'message' => 'Error al eliminar la pregunta']);
}

$stmt->close();
$conn->close();
?>