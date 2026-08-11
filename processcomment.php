<?php
    // start session and connect to db
    session_start();
    include_once("connection.php");
    // redirect back to viewpost.php when done
    header("Location: viewpost.php");
    // remove htmlspecialchars from post data
    array_map("htmlspecialchars", $_POST);

    // use session variables to store if there is an error with the comment
    $_SESSION["commentstatus"] = true;
    $_SESSION["commenterrormsg"] = "";

    // set commentstatus to false if comment is over 1000 characters, and set error message
    if (strlen($_POST["commentcontent"]) > 1000) {
        $_SESSION["commentstatus"] = false;
        $_SESSION["commenterrormsg"] = "Comment is over 1000 characters";
    }

    // insert comment into database if no errors
    if ($_SESSION["commentstatus"] == true) {
        $stmt = $conn->prepare("
        INSERT INTO tblcomments
        (CommentID, UserID, PostID, CommentContent, CommentTime, CommentLikes, CommentDislikes)
        VALUES
        (NULL, :UserID, :PostID, :CommentContent, CURRENT_TIMESTAMP(), 0, 0);
        ");
        // bind params and execute
        $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
        $stmt->bindParam(":PostID", $_SESSION["savedpostid"]);
        $stmt->bindParam(":CommentContent", $_POST["commentcontent"]);
        $stmt->execute();
    }
?>