<?php
    // start session and redirect back to index.php when done
    session_start();
    header("Location: index.php");

    // switch statement to go through all filters
    switch($_POST["sortby"]) {
        case "mostlikes":
            $sortby = "PostLikes DESC";
        case "leastlikes":
            $sortby = "PostLikes ASC";
        case "mostdislikes":
            $sortby = "PostDislikes DESC";
        case "leastdislikes":
            $sortby = "PostDislikes ASC";
        case "newest":
            $sortby = "PostTime DESC";
        case "oldest":
            $sortby = "PostTime ASC";
    }
?>