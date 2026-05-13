<?php
    // start session
    session_start();
    // connect to db
    include_once("connection.php");
    // redirect back to accountpage.php when done
    header("Location: accountpage.php");

    // select all of users interests
    $stmt = $conn->prepare("
    SELECT * FROM tblinterests WHERE UserID = :UserID;
    ");

    // bind params and execute
    $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
    $stmt->execute();

    // select the interest number of the record to be deleted
    $stmt2 = $conn->prepare("
    SELECT InterestNumber FROM tblinterests WHERE UserID = :UserID AND TopicID = :TopicID;
    ");

    // bind params and execute, topic id to remove will have been posted
    $stmt2->bindParam(":UserID", $_SESSION["loggedinid"]);
    $stmt2->bindParam(":TopicID", $_POST["topicid"]);
    $stmt2->execute();
    
    $rowtodelete = $stmt2->fetch(PDO::FETCH_ASSOC);

    // remove interest from tblinterests
    $stmt3 = $conn->prepare("
    DELETE FROM tblinterests WHERE UserID = :UserID AND TopicID = :TopicID;
    ");
    // bind params and execute
    $stmt3->bindParam(":UserID", $_SESSION["loggedinid"]);
    // topic id to remove will have been posted
    $stmt3->bindParam(":TopicID", $_POST["topicid"]);
    $stmt3->execute();

    // loop through user's interests and update interestnumber where appropriate
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // if interestnumber is larger than interestnumber of interest to be removed, then it must be decremented by 1
        $stmt4 = $conn->prepare("
        UPDATE tblinterests SET InterestNumber = InterestNumber - 1 WHERE UserID = :UserID AND InterestNumber > :InterestNumber;
        ");
        $stmt4->bindParam(":UserID", $_SESSION["loggedinid"]);
        $stmt4->bindParam(":InterestNumber", $rowtodelete["InterestNumber"]);
        $stmt4->execute();
    }
?>