<?php
include 'migrate.php';

// Establecer conexión con la base de datos
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "edu";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobación de la conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

// Leer las datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Asignar valores y realizar validaciones
$id = $data['id'] ?? null;
$pregunta = $data['pregunta'] ?? '';
$r1 = $data['r1'] ?? '';
$r2 = $data['r2'] ?? '';
$r3 = $data['r3'] ?? '';
$r4 = $data['r4'] ?? '';
$rcorrecte = $data['rcorrecte'] ?? null;

// Validar que todas las datos son presentes
if ($id === null || empty($pregunta) || empty($r1) || empty($r2) || empty($r3) || empty($r4) || $rcorrecte === null) {
    echo json_encode(['success' => false, 'message' => 'Todas las datos son requeridas.']);
    exit;
}

// Comprobar si rcorrecte es un número válido
if (!is_numeric($rcorrecte) || $rcorrecte < 0 || $rcorrecte > 3) {
    echo json_encode(['success' => false, 'message' => 'La respuesta correcta debe ser un número entre 0 y 3.']);
    exit;
}

// Preparar la consulta para actualizar la pregunta
$sql = "UPDATE preguntes_existents SET pregunta=?, r1=?, r2=?, r3=?, r4=?, rcorrecte=? WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind params y ejecutar
    $stmt->bind_param("ssssssi", $pregunta, $r1, $r2, $r3, $r4, $rcorrecte, $id);
    $stmt->execute();

    // Comprobación
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta actualizada con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la pregunta, o no hubo cambios.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
}

$conn->close();
?>
