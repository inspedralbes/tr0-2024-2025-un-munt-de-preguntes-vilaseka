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

// obtenim només la id des del POST
$id = $_POST['id'] ?? null;

// Validar que la id sigui present i sigui un número
if ($id === null || !is_numeric($id)) {
    echo json_encode(['success' => false, 'message' => 'ID no vàlid.']);
    exit;
}

// eliminem la pregunta
$sql = "DELETE FROM preguntes_existents WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // comprovació 
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta eliminada.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No s\'ha pogut eliminar la pregunta o no existeix.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error en preparar la consulta.']);
}

$conn->close();
?>
