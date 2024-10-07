<?php
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
$numPreguntes = (int) $_GET['num'];

if (isset($_GET['num'])) {
    $num = $_GET['num'];

    // Prepara la consulta SQL
    $sql = "SELECT id, pregunta,r1,r2,r3,r4 FROM preguntes_existents ORDER BY RAND() LIMIT ?";
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
    //     // var_dump($pregunta['rcorrecte']);
    //    unset($pregunta['rcorrecte']);  // Elimina la respuesta correcta
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
    // foreach ($preguntesSeleccionades as &$pregunta) {
    //     unset($pregunta['rcorrecte']);  // Elimina la respuesta correcta
    // }

    // Retorna las preguntas seleccionadas en formato JSON
    echo json_encode($preguntesSeleccionades);

    // Cierra la declaración
    $stmt->close();
}
$conn->close();

?>