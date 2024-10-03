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

// Leer datos
$data = json_decode(file_get_contents("php://input"), true);

// Obtener el ID de la pregunta a eliminar
$id = $data['id'] ?? null;

// Validar que el ID es válido
if ($id === null || !is_numeric($id)) {
    echo json_encode(['success' => false, 'message' => 'El ID de la pregunta es requerido y debe ser un número.']);
    exit;
}

// Preparar la consulta para eliminar la pregunta
$sql = "DELETE FROM preguntes_existents WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind param y ejecutar
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Comprobación
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta eliminada con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la pregunta, o ya estaba eliminada.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
}

$conn->close();
?>
