<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "edu";

// Crear conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar si la conexión ha fallado
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

// Leer los datos JSON enviados en la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Asignar los valores de los datos
$id = $data['id'] ?? null;
$pregunta = $data['pregunta'] ?? '';
$respostes = $data['respostes'] ?? [];
$resposta_correcta = $data['resposta_correcta'] ?? null;

// Validar que todos los datos son correctos
if ($id === null || empty($pregunta) || count($respostes) !== 4 || $resposta_correcta === null) {
    echo json_encode(['success' => false, 'message' => 'Totes les dades són requerides.']);
    exit;
}

// Validar que la respuesta correcta esté entre 0 y 3
if (!is_numeric($resposta_correcta) || $resposta_correcta < 0 || $resposta_correcta > 3) {
    echo json_encode(['success' => false, 'message' => 'La resposta correcta ha de ser un número entre 0 i 3.']);
    exit;
}

// Preparar la consulta SQL para actualizar la pregunta en la base de datos
$sql = "UPDATE preguntes_existents SET pregunta=?, r1=?, r2=?, r3=?, r4=?, rcorrecte=? WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Vincular parámetros y ejecutar la consulta
    $stmt->bind_param("ssssssi", $pregunta, $respostes[0], $respostes[1], $respostes[2], $respostes[3], $resposta_correcta, $id);
    $stmt->execute();

    // Comprobar si la pregunta se actualizó correctamente
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pregunta actualitzada amb èxit.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No s\'ha pogut actualitzar la pregunta, o no hi ha canvis.']);
    }

    // Cerrar el statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>