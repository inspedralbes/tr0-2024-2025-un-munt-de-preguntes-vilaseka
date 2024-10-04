<?php
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "edu";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$numPreguntes = (int) $_GET['num'];

if (isset($_GET['num'])) {
    $num = $_GET['num'];

    // Prepara la consulta SQL
    $sql = "SELECT * FROM preguntes_existents ORDER BY RAND() LIMIT ?";
    $stmt = $conn->prepare($sql);

    // Víncula el parámetro
    $stmt->bind_param("i", $num);

    // Ejecuta la consulta
    $stmt->execute();

    // Obtén los resultados
    $result = $stmt->get_result();
    $preguntesSeleccionades = $result->fetch_all(MYSQLI_ASSOC); // Obtiene todas las filas como un array asociativo

    // Elimina el campo 'rcorrecte' de cada pregunta
    // foreach ($preguntesSeleccionades as &$pregunta) {
    //     unset($pregunta['rcorrecte']);  // Elimina la respuesta correcta
    // }

    // Retorna las preguntas seleccionadas en formato JSON
    echo json_encode($preguntesSeleccionades);

    // Cierra la declaración
    $stmt->close();
}

// Cierra la conexión
else {
    $sql = "SELECT * FROM preguntes_existents";
    $stmt = $conn->prepare($sql);

    // Ejecuta la consulta
    $stmt->execute();

    // Obtén los resultados
    $result = $stmt->get_result();
    $preguntesSeleccionades = $result->fetch_all(MYSQLI_ASSOC); // Obtiene todas las filas como un array asociativo

    // Elimina el campo 'rcorrecte' de cada pregunta
    foreach ($preguntesSeleccionades as &$pregunta) {
        unset($pregunta['rcorrecte']);  // Elimina la respuesta correcta
    }

    // Retorna las preguntas seleccionadas en formato JSON
    echo json_encode($preguntesSeleccionades);

    // Cierra la declaración
    $stmt->close();
}
$conn->close();

?>