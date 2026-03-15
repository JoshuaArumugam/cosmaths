<?php
    // establish connection to database
    include_once('connection.php');
    // ensure database exists
    $stmt = "CREATE DATABASE IF NOT EXISTS cosmaths";
    $conn->exec($stmt);

    // echo so I can see if the code has ran correctly
    echo("DB made<br>");

    // using correct database for project
    $stmt = "USE cosmaths";
    $conn->exec($stmt);

    // creating tblusers, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tblusers;
    CREATE TABLE tblusers
    (UserID INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(20) NOT NULL,
    Email VARCHAR(254) NOT NULL,
    Password VARCHAR(256) NOT NULL);
    ");
    $stmt->execute();

    // adding test data to tblusers
    $stmt = $conn->prepare("
    INSERT INTO tblusers
    (UserID, Username, Email, Password)
    VALUES
    (NULL, 'Alexsmells', 'alexchan@gmail.com', :Password)
    ");

    // hashing password
    $hashedpassword = password_hash("AlexChan58", PASSWORD_DEFAULT);

    // bind hashed password to insert statement
    $stmt->bindParam(":Password", $hashedpassword);
    $stmt->execute();

    echo("tblusers made<br>");

    // creating tblinterests, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tblinterests;
    CREATE TABLE tblinterests
    (UserID INT(4) UNSIGNED NOT NULL,
    InterestNumber TINYINT(2) UNSIGNED NOT NULL,
    TopicID TINYINT(2) UNSIGNED NOT NULL,
    PRIMARY KEY (UserID, InterestNumber));
    ");
    $stmt->execute();

    // adding test data to tblinterests
    $stmt = $conn->prepare("
    INSERT INTO tblinterests
    (UserID, InterestNumber, TopicID)
    VALUES
    (1, 0, 1)
    ");
    $stmt->execute();

    $stmt = $conn->prepare("
    INSERT INTO tblinterests
    (UserID, InterestNumber, TopicID)
    VALUES
    (1, 1, 2)
    ");
    $stmt->execute();

    $stmt = $conn->prepare("
    INSERT INTO tblinterests
    (UserID, InterestNumber, TopicID)
    VALUES
    (1, 2, 3)
    ");
    $stmt->execute();

    echo("tblinterests made<br>");

    // creating tbltopiclabels, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tbltopiclabels;
    CREATE TABLE tbltopiclabels
    (TopicID TINYINT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TopicName VARCHAR(60) NOT NULL);
    ");
    $stmt->execute();

    // adding test data to tbltopiclabels
    $stmt = $conn->prepare("
    INSERT INTO tbltopiclabels
    (TopicID, TopicName)
    VALUES
    (NULL, 'Algebra')
    ");
    $stmt->execute();

    $stmt = $conn->prepare("
    INSERT INTO tbltopiclabels
    (TopicID, TopicName)
    VALUES
    (NULL, 'Geometry')
    ");
    $stmt->execute();

    $stmt = $conn->prepare("
    INSERT INTO tbltopiclabels
    (TopicID, TopicName)
    VALUES
    (NULL, 'Calculus')
    ");
    $stmt->execute();

    echo("tbltopiclabels made<br>");

    // creating tblposts, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tblposts;
    CREATE TABLE tblposts
    (PostID INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UserID INT(4) UNSIGNED NOT NULL,
    PostContent VARCHAR(1000) NOT NULL,
    PostTitle VARCHAR(300) NOT NULL,
    PostTime DATETIME NOT NULL,
    PostLikes INT(4) UNSIGNED NOT NULL,
    PostDislikes INT(4) UNSIGNED NOT NULL,
    IsQuestion TINYINT(1) UNSIGNED NOT NULL,
    QuestionAnswer DECIMAL(8, 2),
    QuestionHint VARCHAR(300));
    ");
    $stmt->execute();

    // adding test data to tblposts
    $stmt = $conn->prepare("
    INSERT INTO tblposts
    (PostID, UserID, PostContent, PostTitle, PostTime, PostLikes, PostDislikes, IsQuestion, QuestionAnswer, QuestionHint)
    VALUES
    (NULL, 1, 'This post is about how much I love maths it is the best subject in the world I love it so much', 'How much I love maths', CURRENT_TIMESTAMP(), 5, 2, 0, NULL, NULL)
    ");
    $stmt->execute();

    $stmt = $conn->prepare("
    INSERT INTO tblposts
    (PostID, UserID, PostContent, PostTitle, PostTime, PostLikes, PostDislikes, IsQuestion, QuestionAnswer, QuestionHint)
    VALUES
    (NULL, 1, 'Solve this very hard equation: \(x+5=7\)', 'Very hard equation', CURRENT_TIMESTAMP(), 5, 2, 1, 2, 'Subtract 5 from both sides')
    ");
    $stmt->execute();

    echo("tblposts made<br>");

    // creating tblpoststags, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tblpoststags;
    CREATE TABLE tblpoststags
    (PostID INT(4) UNSIGNED NOT NULL,
    TopicNumber TINYINT(2) UNSIGNED NOT NULL,
    TopicID TINYINT(2) UNSIGNED NOT NULL,
    PRIMARY KEY (PostID, TopicNumber));
    ");
    $stmt->execute();

    // adding test data to tblpoststags
    $stmt = $conn->prepare("
    INSERT INTO tblpoststags
    (PostID, TopicNumber, TopicID)
    VALUES
    (1, 0, 1)
    ");
    $stmt->execute();

    $stmt = $conn->prepare("
    INSERT INTO tblpoststags
    (PostID, TopicNumber, TopicID)
    VALUES
    (2, 0, 2)
    ");
    $stmt->execute();

    echo("tblpoststags made<br>");

    // creating tblcomments, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tblcomments;
    CREATE TABLE tblcomments
    (CommentID INT(6) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    UserID INT(4) UNSIGNED NOT NULL,
    PostID INT(4) UNSIGNED NOT NULL,
    CommentContent VARCHAR(1000) NOT NULL,
    CommentTime DATETIME NOT NULL,
    CommentLikes INT(4) UNSIGNED NOT NULL,
    CommentDislikes INT(4) UNSIGNED NOT NULL);
    ");
    $stmt->execute();

    // adding test data to tblcomments
    $stmt = $conn->prepare("
    INSERT INTO tblcomments
    (CommentID, UserID, PostID, CommentContent, CommentTime, CommentLikes, CommentDislikes)
    VALUES
    (NULL, 1, 1, 'This is the best post I\'ve ever seen in my life', CURRENT_TIMESTAMP(), 100, 3)
    ");
    $stmt->execute();

    $stmt = $conn->prepare("
    INSERT INTO tblcomments
    (CommentID, UserID, PostID, CommentContent, CommentTime, CommentLikes, CommentDislikes)
    VALUES
    (NULL, 1, 2, 'Omg this question is really hard >:(', CURRENT_TIMESTAMP(), 2, 1000)
    ");
    $stmt->execute();

    echo("tblcomments made<br>");
?>
