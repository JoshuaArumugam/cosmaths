<?php
    // connect to db, start session, redirect back to index.php when done
    include_once('connection.php');
    session_start();

    // set made search to 1
    $_SESSION["madesearch"] = 1;
    
    header("Location: index.php");

    // prevent sql injection
    array_map("htmlspecialchars", $_POST);

    // select all posts where search query is in post title, content or username
    // make the fields lowercase so it doesn't matter if the text in the post is uppercase or not
    $stmt = $conn->prepare("
    SELECT * FROM tblposts WHERE LOWER(PostContent) LIKE CONCAT('%', :searchquery, '%')
    OR LOWER(PostTitle) LIKE CONCAT('%', :searchquery, '%')
    OR UserID IN (SELECT UserID FROM tblusers WHERE LOWER(Username) LIKE CONCAT('%', :searchquery, '%'));
    ");
    // bind params and execute
    $stmt->bindParam(":searchquery", $_POST["searchquery"]);
    $stmt->execute();

    // store result of statement in session variable
    $_SESSION["searchresults"] = $stmt;
?>