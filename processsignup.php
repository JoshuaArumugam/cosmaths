<?php
    // remove htmlspecialchars from input data to prevent injection
    array_map("htmlspecialchars", $_POST);
    // connect to db
    include_once("connection.php");
    // start session so session variables can be used
    session_start();

    // switch statement to go through all cases of invalid information
    switch(true) {
        // username too long
        case strlen($_POST["username"]) > 20:
            // set error message, sign up status and break
            $_SESSION["signuperrormsg"] = "Username exceeds 20 characters";
            $_SESSION["signupstatus"] = false;
            break;
        case strlen($_POST["email"]) > 254:
            // same as previous case, but different error msg
            $_SESSION["signuperrormsg"] = "Email exceeds 254 characters";
            $_SESSION["signupstatus"] = false;
            break;
    }
?>