<?php
    // remove htmlspecialchars from input data to prevent injection
    array_map("htmlspecialchars", $_POST);
    // connect to db
    include_once("connection.php");
    // start session so session variables can be used
    session_start();

    // retrieve user info from tblusers using email
    $stmt = $conn->prepare("
    SELECT * FROM tblusers WHERE Email=:Email;
    ");
    // bind params and execute
    $stmt->bindParam(":Email", $_POST["email"]);
    $stmt->execute();
    // store returned record
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // if account with corresponding email found
    if ($row) {
        // there should only be 1 row returned as the email of each account is unique
        while ($row) {
            // check if hashed passwords match
            $hashed = $row["Password"];
            $attempt = $_POST["password"];
            if (password_verify($attempt, $hashed)) {
                // if they match then store relevant info in session variables
                $_SESSION["username"] = $row["Username"];
                $_SESSION["email"] = $row["Email"];
                $_SESSION["loggedinid"] = $row["UserID"];
                $_SESSION["loginstatus"] = true;
                // redirect to homepage
                header("Location: index.php");
            }
            else {
                // password wrong, redirect to login
                $_SESSION["loginstatus"] = false;
                $_SESSION["loginerrormsg"] = "Incorrect email or password";
                header("Location: login.php");
            }
        }
    }
    // else means that there was no account found with entered email
    else {
        // redirect to login
        $_SESSION["loginstatus"] = false;
        $_SESSION["loginerrormsg"] = "Incorrect email or password";
        header("Location: login.php");
    }
?>

