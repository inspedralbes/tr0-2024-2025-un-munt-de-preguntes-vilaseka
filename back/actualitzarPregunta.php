<?php
include 'migrate.php';

// establim connexio amb la base de dades
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "edu";

// creem la connexio
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprovació de la connexió
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Llegir les dades JSON del cos de la petició
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$pregunta = $data['pregunta'] ?? '';
$r1 = $data['r1'] ?? '';
$r2 = $data['r2'] ?? '';
$r3 = $data['r3'] ?? '';
$r4 = $data['r4'] ?? '';
$rcorrecte = $data['rcorrecte'] ?? null;

// Validar que totes les dades són presents
if ($id === null || empty($pregunta) || empty($r1) || empty($r2) || empty($r3) || empty($r4) || $rcorrecte === null) {
    echo json_encode(['success' => false, 'message' => 'Totes les dades són requerides.']);
    exit;
}

// Comprovació si rcorrecte és un número vàlid
if (!is_numeric($rcorrecte) || $rcorrecte < 0 || $rcorrecte > 3) {
    echo json_encode(['success' => false, 'message' => 'La resposta correcta ha de ser un número entre 0 i 3.']);
    exit;
}

// preparar la query per actualitzar la pregunta
$sql = "UPDATE preguntes_existents SET pregunta=?, r1=?, r2=?, r3=?, r4=?, rcorrecte=? WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // bind params i executar
    $stmt->bind_param("ssssssi", $pregunta, $r1, $r2, $r3, $r4, $rcorrecte, $id);
    $stmt->execute();

    // comprovació
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta actualitzada!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No s\'ha pogut actualitzar la pregunta.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error en preparar la consulta.']);
}

$conn->close();
?>
