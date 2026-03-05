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
    (UserID INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Username VARCHAR(20) NOT NULL,
    Email VARCHAR(254) NOT NULL,
    Password VARCHAR(256) NOT NULL);
    ");
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

    echo("tblinterests made<br>");

    // creating tbltopiclabels, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tbltopiclabels;
    CREATE TABLE tbltopiclabels
    (TopicID TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    TopicName VARCHAR(60) NOT NULL);
    ");
    $stmt->execute();

    echo("tbltopiclabels made<br>");

    // creating tblposts, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tblposts;
    CREATE TABLE tblposts
    (PostID INT(4) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
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

    echo("tblpoststags made<br>");

    // creating tblcomments, resets it if it already exists
    $stmt = $conn->prepare("DROP TABLE IF EXISTS tblcomments;
    CREATE TABLE tblcomments
    (CommentID INT(6) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    UserID INT(4) UNSIGNED NOT NULL,
    PostID INT(4) UNSIGNED NOT NULL,
    CommentContent VARCHAR(1000) NOT NULL,
    CommentTime DATETIME NOT NULL,
    CommentLikes INT(4) UNSIGNED NOT NULL,
    CommentDislikes INT(4) UNSIGNED NOT NULL);
    ");
    $stmt->execute();

    echo("tblcomments made<br>");
?>
