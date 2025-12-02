<?php
require "bd.php";

if(isset($_POST['deleteActivityID'])) {
    $id = $conn->real_escape_string($_POST['deleteActivityID']);
    
    // RECUPERAR LA PÁGINA DE RETORNO (O DEFAULT A SPELLING.PHP)
    $redirectPage = isset($_POST['redirect']) ? $_POST['redirect'] : '../spelling.php';

    // 1. Borrar calificaciones
    $conn->query("DELETE FROM grades WHERE activityID = '$id'");

    // 2. Borrar actividad
    $sql = "DELETE FROM activities WHERE activityID = '$id'";

    if($conn->query($sql)){
        // Redirigir a la página correcta
        header("Location: " . $redirectPage);
    } else {
        echo "Error al borrar: " . $conn->error;
    }
} else {
    echo "Falta el ID de la actividad";
}
?>