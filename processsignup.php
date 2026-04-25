<?php
    // redirect back to signup.php
    header("Location: signup.php");
    // remove htmlspecialchars from input data to prevent injection
    array_map("htmlspecialchars", $_POST);
    // connect to db
    include_once("connection.php");
    // start session so session variables can be used
    session_start();

    // default sign up status to true
    $_SESSION["signupstatus"] = true;

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
        case !str_contains($_POST["email"], '@'):
            // checks for @ symbol in email
            $_SESSION["signuperrormsg"] = "Email does not include @ symbol";
            $_SESSION["signupstatus"] = false;
            break;
    }
    // select all rows where username matches inputted username
    $stmt = $conn->prepare("
    SELECT * FROM tblusers WHERE Username=:Username
    ");
    $stmt->bindParam(":Username", $_POST["username"]);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // if row is not null then that means someone already has that username
    if ($row) {
        // set signupstatus to false and error msg
        $_SESSION["signuperrormsg"] = "Username is already taken";
        $_SESSION["signupstatus"] = false;
    }

    // repeat but for email address instead
    $stmt = $conn->prepare("
    SELECT * FROM tblusers WHERE Email=:Email
    ");
    $stmt->bindParam(":Email", $_POST["email"]);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // if row is not null then that means someone already has that email
    if ($row) {
        // set signupstatus to false and error msg
        $_SESSION["signuperrormsg"] = "User already exists with same email address";
        $_SESSION["signupstatus"] = false;
    }
    if ($_SESSION["signupstatus"]) {
        // insert statement
        $stmt = $conn->prepare("
        INSERT INTO tblusers
        (UserID, Username, Email, Password)
        VALUES
        (NULL, :Username, :Email, :Password)
        ");
        // hashing password
        $hashedpassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // bind params, hashed password to insert statement
        $stmt->bindParam(":Username", $_POST["username"]);
        $stmt->bindParam(":Email", $_POST["email"]);
        $stmt->bindParam(":Password", $hashedpassword);
        $stmt->execute();
    }
?>

