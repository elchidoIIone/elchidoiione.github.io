<?php
require "bd.php";

if(isset($_POST['studentID'])) {
    $studentID = $_POST['studentID'];
    $activityID = $_POST['activityID'];
    $grade = $_POST['grade'];

    // Validación estricta en el servidor
    if($grade !== "") {
        if($grade < 0) $grade = 0;
        if($grade > 100) $grade = 100;
    } else {
        $grade = 0;
    }

    $check = $conn->query("SELECT * FROM grades WHERE studentsID = $studentID AND activityID = $activityID");

    if ($check->num_rows > 0) {
        $conn->query("UPDATE grades SET grade = '$grade' WHERE studentsID = $studentID AND activityID = $activityID");
    } else {
        $conn->query("INSERT INTO grades (studentsID, activityID, grade) VALUES ($studentID, $activityID, '$grade')");
    }
    
    echo "Guardado Correctamente";
}
?>