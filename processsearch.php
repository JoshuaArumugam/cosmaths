<?php
    // connect to db, start session, redirect back to index.php when done
    include_once('connection.php');
    session_start();
    header("Location: index.php");

    // prevent sql injection
    array_map("htmlspecialchars", $_POST);

    // select all posts where search query is in post title, content or username
?>