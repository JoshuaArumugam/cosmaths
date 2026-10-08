<?php
    session_start();
    header("Location: login.php");
    unset($_SESSION["username"], $_SESSION["email"], $_SESSION["loggedinid"], $_SESSION["loginstatus"], $_SESSION["loginerrormsg"]);
?>