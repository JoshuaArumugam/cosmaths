<?php
    // start session, and redirect to viewpost.php when finished
    session_start();
    header("Location: viewpost.php");

    // save postid to session variable
    $_SESSION['savedpostid'] = $_POST['PostID'];
?>