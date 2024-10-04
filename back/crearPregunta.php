<?php
include 'migrate.php';
// Establecer la conexión a la base de datos
$servername = "localhost";
$username = "a23eduvilvil_BDPROJECTE1";
$password = "1035papA.";
$dbname = "a23eduvilvil_BDPROJECTE1";
// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del cuerpo de la solicitud
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Debug: Imprimir datos recibidos
if ($data === null) {
    echo json_encode(["success" => false, "message" => "Error al decodificar JSON."]);
    exit;
}

// Verificar que se recibieron los datos correctamente
if (isset($data['pregunta'], $data['respostes'], $data['resposta_correcta'])) {
    $pregunta = $conn->real_escape_string($data['pregunta']);
    $resposta_correcta = (int) $data['resposta_correcta'];

    // Asegurarse de que las respuestas son válidas
    $respostes = $data['respostes'];
    if (count($respostes) === 4) {
        // Escapar cada respuesta para evitar inyecciones SQL
        $r1 = $conn->real_escape_string($respostes[0]);
        $r2 = $conn->real_escape_string($respostes[1]);
        $r3 = $conn->real_escape_string($respostes[2]);
        $r4 = $conn->real_escape_string($respostes[3]);

        // Consulta para insertar la nueva pregunta en la tabla correcta
        $sql = "INSERT INTO preguntes_existents (pregunta, r1, r2, r3, r4, rcorrecte) VALUES (?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Vincular parámetros
            $stmt->bind_param("sssssi", $pregunta, $r1, $r2, $r3, $r4, $resposta_correcta);
            if ($stmt->execute()) {
                // Comprobación
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Pregunta afegida correctament!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No s\'ha pogut afegir la pregunta.']);
                }
            } else {
                // Error en la ejecución de la consulta
                echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta: ' . $stmt->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en preparar la consulta.']);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Se requieren exactamente 4 respuestas."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
}

// Cerrar la conexión
$conn->close();
?>