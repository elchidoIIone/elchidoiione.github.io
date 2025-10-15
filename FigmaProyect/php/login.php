<?php

    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "betitoindio";

    $conn = new mysqli("localhost", $user, $pass, $dbname);
    if($conn -> connect_error){
        die("Error de concexión");
    }


    $user = $_POST["user"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM login WHERE user='$user' AND password ='$password'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        echo "<script> alert('login correcto') </script>";
        header("Location: ../index.html");
        mysqli_close($conn);
        exit;   
    } 
    else{
        echo "<script> alert('incorrect login') </script>";
        header("Location: ../login.html");
    }

    

    
    
    
?>