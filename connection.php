<?php
    // information for connecting to database
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "cosmaths";

    // establish connection with PDO object
    try{
        $conn = new PDO("mysql:host=$servernamel; dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    // show the error if there is one
    catch (PDOException $e) {
        echo("connection failed " . $e->getMessage() ."<br>");
    }
?>

