<?php
// establim connexio amb la base de dades
$servername = "localhost";
$username = "edu";
$password = "2024";
$dbname = "edu";

// creem la connexio
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection (w3schools) verificaio
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//aqui elimino la taula si existeix
$sqlDrop = "DROP TABLE IF EXISTS preguntes_existents;";

//missatge de error
if ($conn->query($sqlDrop) === TRUE) {
    // echo "<br>Taula elimanada si existia.<br>";
} else {
    // echo "Error al elimminar la taula: " . $conn->error . "</br>";
}

// creacio de taula
$sqlCreate = "CREATE TABLE `preguntes_existents` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pregunta` varchar(100) NOT NULL,
    `r1` varchar(30) NOT NULL,
    `r2` varchar(30) NOT NULL,
    `r3` varchar(30) NOT NULL,
    `r4` varchar(30) NOT NULL,
    `rcorrecte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";


//missatge de error
if ($conn->query($sqlCreate) === TRUE) {
    // echo "Taula creada correctament. <br>";
} else {
    //echo "Error al crear la taula " . $conn->error . "</br>";
}

//llegim el fitxer json
$json = file_get_contents('data.json');
$data = json_decode($json, true);


$stmt = $conn->prepare("INSERT INTO preguntes_existents (id, pregunta, r1, r2, r3, r4, rcorrecte) VALUES (?, ?, ?, ?, ?, ?, ?)");

foreach ($data['preguntes'] as $row) {
    $id = $row['id'];
    $pregunta = $row['pregunta'];
    $r1 = $row['respostes'][0];
    $r2 = $row['respostes'][1];
    $r3 = $row['respostes'][2];
    $r4 = $row['respostes'][3];
    $rcorrecte = $row['resposta_correcta'];

    // Enllaçem les variables a la sentència preparada
    $stmt->bind_param("isssssi", $id, $pregunta, $r1, $r2, $r3, $r4, $rcorrecte);

    // Executem la consulta
    if ($stmt->execute()) {
        // echo "Dades inserides correctament.<br>";
    } else {
        //echo "Error en inserir dades: " . $stmt->error . "<br>";
    }
}
$stmt->close();
$conn->close();

?>