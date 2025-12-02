<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "betitoindio";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$name = $_POST['name'];
$dadName = $_POST['dadName'];
$momName = $_POST['momName'];
$cicle = $_POST['cicle'];

$sql = "INSERT INTO students (`name`, `dadName`, `momName`, `cicle`) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $name, $dadName, $momName, $cicle);

    if ($stmt->execute()) {
        echo "<h1>¡Registro exitoso!</h1>";
        echo "<p>El estudiante " . htmlspecialchars($name) . " " . htmlspecialchars($dadName) . " ha sido añadido a la base de datos.</p>";
        echo '<a href="../students.html">Añadir otro estudiante</a>';
        
    } else {
        echo "<h1>Error al registrar.</h1>";
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<h1>Error en la preparación de la consulta.</h1>";
    echo "<p>Error: " . $conn->error . "</p>";
}

$conn->close();

?>

