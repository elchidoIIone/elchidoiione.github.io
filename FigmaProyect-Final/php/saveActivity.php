<?php
require "bd.php"; 

if(isset($_POST['activityName']) && isset($_POST['subjectID'])) {
    
    $subjectID = $_POST['subjectID'];
    // Usamos trim() para quitar espacios al inicio o final por accidente
    $name = $conn->real_escape_string(trim($_POST['activityName']));
    
    // --- CAMBIO CLAVE: RECUPERAR LA PÁGINA DE RETORNO ---
    // Si el formulario envió "redirect", usamos esa ruta. Si no, volvemos a spelling.php por defecto.
    $redirectPage = isset($_POST['redirect']) ? $_POST['redirect'] : '../spelling.php';

    // 1. VERIFICAR SI YA EXISTE (Anti-duplicados)
    // Buscamos si hay alguna actividad con el MISMO nombre y la MISMA materia
    $checkQuery = "SELECT activityID FROM activities WHERE activityName = '$name' AND subjectID = '$subjectID'";
    $checkResult = $conn->query($checkQuery);

    if($checkResult->num_rows > 0) {
        // SI YA EXISTE: Regresamos con un error en la URL, pero a la página correcta ($redirectPage)
        
        // Revisamos si la URL ya tiene signos de interrogación (?) para saber cómo unir el error
        // Si ya tiene parámetros (ej: math.php?id=1), usamos '&'. Si no, usamos '?'.
        $separator = (strpos($redirectPage, '?') === false) ? '?' : '&';
        
        header("Location: " . $redirectPage . $separator . "error=duplicate");
        exit; // Detenemos el script aquí
    }

    // 2. SI NO EXISTE, INSERTAMOS LA ACTIVIDAD
    $sql = "INSERT INTO activities (activityName, subjectID) VALUES ('$name', '$subjectID')";
    
    if($conn->query($sql)){
        // ÉXITO: Redirigir dinámicamente a la página desde donde vinimos
        header("Location: " . $redirectPage); 
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Faltan datos";
}
?>