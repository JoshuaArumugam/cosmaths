<?php
    // start session, redirect back to index.php when done
    session_start();
    
    header("Location: index.php");

    // prevent sql injection
    array_map("htmlspecialchars", $_POST);
    
    // store search query in session variable
    $_SESSION["searchquery"] = $_POST["searchquery"];

    // set made search to 1
    $_SESSION["madesearch"] = 1;
?>