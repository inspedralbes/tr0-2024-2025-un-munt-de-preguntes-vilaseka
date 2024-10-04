<?php
// Configuració de la base de dades
$servername = "localhost";
$username = "a23eduvilvil_BDPROJECTE1";
$password = "1035papA.";
$dbname = "a23eduvilvil_BDPROJECTE1";

// Crear la connexió amb la base de dades
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprovar la connexió
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de connexió: ' . $conn->connect_error]);
    exit;
}

// Obtenir l'ID de la pregunta des de la URL (GET)
$id = $_GET['id'] ?? null;

// Validar que s'hagi proporcionat un ID
if ($id === null) {
    echo json_encode(['success' => false, 'message' => 'No s\'ha proporcionat cap ID.']);
    exit;
}

// Preparar la consulta SQL per eliminar la pregunta
$sql = "DELETE FROM preguntes_existents WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Assignar l'ID a la consulta preparada
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Comprovar si l'eliminació ha estat exitosa
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta eliminada amb èxit.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No s\'ha trobat la pregunta amb aquest ID.']);
    }

    // Tancar la consulta preparada
    $stmt->close();
} else {
    // En cas d'error en preparar la consulta
    echo json_encode(['success' => false, 'message' => 'Error en preparar la consulta.']);
}

// Tancar la connexió
$conn->close();
?>