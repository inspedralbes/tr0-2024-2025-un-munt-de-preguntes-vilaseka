<?php
include 'migrate.php';

// establim connexio amb la base de dades
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "edu";

// creem la connexio
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//obtenir dades per POST
$id = $_POST['id'] ?? null; // Usar null si 'id' no existeix
$pregunta = $_POST['pregunta'] ?? '';
$r1 = $_POST['r1'] ?? '';
$r2 = $_POST['r2'] ?? '';
$r3 = $_POST['r3'] ?? '';
$r4 = $_POST['r4'] ?? '';
$rcorrecte = $_POST['rcorrecte'] ?? null;

// Verificar si id és vàlid
if ($id === null || !is_numeric($id)) {
    echo json_encode(['succes' => false, 'message' => 'ID no vàlid']);
    exit;
}

//actualitzem la pregunta
$sql = "UPDATE preguntes_existents SET pregunta=?, r1=?, r2=?, r3=?, r4=?, rcorrecte=? WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind params i executar
    $stmt->bind_param("ssssssi", $pregunta, $r1, $r2, $r3, $r4, $rcorrecte, $id);
    $stmt->execute();

    // Comprovació
    if ($stmt->affected_rows > 0) {
        echo json_encode(['succes' => true, 'message' => 'Pregunta actualitzada!']);
    } else {
        echo json_encode(['succes' => false, 'message' => 'No s\'ha actualitzat cap pregunta o la informació és la mateixa.']);
    }

    $stmt->close();
} else {
    echo json_encode(['succes' => false, 'message' => 'Error en preparar la consulta']);
}

$conn->close();
?>
