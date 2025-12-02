<?php
session_start();

require "bd.php";


if (!isset($_POST["user"]) || !isset($_POST["password"])) {
    header("Location: ../index.html?error=empty");
    exit;
}

$userForm = $_POST["user"];
$passwordForm = $_POST["password"];


$sql = "SELECT * FROM users WHERE user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userForm);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();


    if ($passwordForm === $userData["password"]) {

        
        $_SESSION["user_login"] = $userData["user"]; 
        

        header("Location: ../spelling.php");
        exit;
    }
}


header("Location: ../index.html?error=invalid");
exit;
?>