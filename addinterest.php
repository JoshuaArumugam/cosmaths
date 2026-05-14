<?php
    // start session
    session_start();
    // connect to db
    include_once("connection.php");
    // redirect back to accountpage.php when done
    header("Location: accountpage.php");

    // first need to check if user already has that interest in their interest list
    $stmt = $conn->prepare("
    SELECT * FROM tblinterests WHERE UserID = :UserID AND TopicID = :TopicID;
    ");
    // bind params and execute
    $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
    $stmt->bindParam(":TopicID", $_POST["interesttoadd"]);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // if nothing found, then that means interest can be added
    if (!$row) {
        // find the largest interestnumber, to be used later
        $stmt = $conn->prepare("
        SELECT MAX(InterestNumber) FROM tblinterests WHERE UserID = :UserID;
        ");
        // bind params and execute
        $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $largest = $row["MAX(InterestNumber)"] + 1;

        // insert new interest into table
        $stmt2 = $conn->prepare("
        INSERT INTO tblinterests
        (UserID, InterestNumber, TopicID)
        VALUES
        (:UserID, :InterestNumber, :TopicID)
        ");
        // bind params and execute
        $stmt2->bindParam(":UserID", $_SESSION["loggedinid"]);
        $stmt2->bindParam(":InterestNumber", $largest);
        $stmt2->bindParam(":TopicID", $_POST["interesttoadd"]);
        $stmt2->execute();
    } 
?>