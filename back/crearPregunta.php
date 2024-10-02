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
echo "Connected successfully <br>";      

//obtenir les dades del POST
$pregunta = $_POST['pregunta'] ?? '';
$r1 = $_POST['r1'] ?? '';
$r2 = $_POST['r2'] ?? '';
$r3 = $_POST['r3'] ?? '';
$r4 = $_POST['r4'] ?? '';
$rcorrecte = $_POST['rcorrecte'] ?? null;

//comprovacions
// // Validar que totes les dades són presents
// if (empty($pregunta) || empty($r1) || empty($r2) || empty($r3) || empty($r4) || $rcorrecte === null) {
//     echo json_encode(['success' => false, 'message' => 'Totes les dades són requerides.']);
//     exit;
// }

// // Comprovació si rcorrecte és un número vàlid
// if (!is_numeric($rcorrecte) || $rcorrecte < 0 || $rcorrecte > 3) {
//     echo json_encode(['success' => false, 'message' => 'La resposta correcta ha de ser un número entre 0 i 3.']);
//     exit;
// }
$rcorrecte = intval($_POST['rcorrecte']);
// preparar la query per inserir la pregunta
$sql = "INSERT INTO preguntes_existents (pregunta, r1, r2, r3, r4, rcorrecte) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // bind params i executar
    $stmt->bind_param("sssssi", $pregunta, $r1, $r2, $r3, $r4, $rcorrecte);
    $stmt->execute();

    // comprovació
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta afegida correctament!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No s\'ha pogut afegir la pregunta.']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error en preparar la consulta.']);
}

$conn->close();
?>
