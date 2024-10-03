<?php
include 'migrate.php';
// establim connexio amb la base de dades
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "edu";

//creem la connexio
$conn = new mysqli($servername, $username, $password, $dbname);

//comprovació de la connexió
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//llegir dades
$data = json_decode(file_get_contents("php://input"), true);

//obtenir l'ID de la pregunta a eliminar
$id = $data['id'] ?? null;

//validar que l'ID és vàlid (comprovacions)
if ($id === null) {
    echo json_encode(['success' => false, 'message' => 'ID de la pregunta és requerit.']);
    exit;
}

// Preparar la consulta per eliminar la pregunta
$sql = "DELETE FROM preguntes_existents WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // bind param i executar
    $stmt->bind_param("i", $id);
    $stmt->execute();

    //comprovació
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta eliminada.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No s\'ha pogut eliminar la pregunta o ja estava eliminada.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error en preparar la consulta.']);
}

$conn->close();
?>