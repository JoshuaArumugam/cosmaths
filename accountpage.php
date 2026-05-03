<?php
    // start session
    session_start();
    // check if loginstatus is set, or if it is false
     if (isset($_SESSION["loginstatus"])) {
        if (!$_SESSION["loginstatus"]) {
            header("Location: login.php");
        }
     }
     else {
        header("Location: login.php");
     }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CosMaths Account</title>
    </head>
    <body>
        <h1>Account Information</h1>
        <p>Username</p>
        <p>Email</p>
        <h3>Interests</h3>
        <h3>Posts</h3>
    </body>
</html>