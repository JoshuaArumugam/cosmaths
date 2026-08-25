<?php
    // start session and connect to db
    session_start();
    include_once("connection.php");
    // redirect back to viewpost.php when done
    header("Location: viewpost.php");

    // first check if user is liking or disliking the post
    if ($_POST["reaction"] == "like") {
        // if user has already disliked the post, then the cookie for the dislike must be removed
        if (isset($_COOKIE["post_" . $_POST["postid"] . "_dislike"])) {
            // delete cookie
            setcookie("post_" . $_POST["postid"] . "_dislike", "", time() - 3600, "/");
            // update post dislikes in db
            $stmt = $conn->prepare("
            UPDATE tblposts SET PostDislikes = PostDislikes - 1 WHERE PostID = :PostID;
            ");
            // bind params and execute
            $stmt->bindParam(":PostID", $_POST["postid"]);
            $stmt->execute();
        }
        // then check if cookie has been set for the user liking this post, if not create it
        if (!isset($_COOKIE["post_" . $_POST["postid"] . "_like"])) {
            // means user hasn't liked the post yet
            // set cookie for 1 year
            setcookie("post_" . $_POST["postid"] . "_like", "true", time() + (31536000), "/");
            // update post likes in db
            $stmt = $conn->prepare("
            UPDATE tblposts SET PostLikes = PostLikes + 1 WHERE PostID = :PostID;
            ");
            // bind params and execute
            $stmt->bindParam(":PostID", $_POST["postid"]);
            $stmt->execute();
        }
        // else check if user has already liked the post or not
        else {
            // means user has already liked the post, so remove the like
            // delete cookie
            setcookie("post_" . $_POST["postid"] . "_like", "", time() - 3600, "/");
            // update post likes in db
            $stmt = $conn->prepare("
            UPDATE tblposts SET PostLikes = PostLikes - 1 WHERE PostID = :PostID;
            ");
            // bind params and execute
            $stmt->bindParam(":PostID", $_POST["postid"]);
            $stmt->execute();
        }
    }
    // else user is disliking, so repeat the same steps but for disliking instead of liking
    else {
        // if user has already liked the post, then the cookie for the like must be removed
        if (isset($_COOKIE["post_" . $_POST["postid"] . "_like"])) {
            // delete cookie
            setcookie("post_" . $_POST["postid"] . "_like", "", time() - 3600, "/");
            // update post likes in db
            $stmt = $conn->prepare("
            UPDATE tblposts SET PostLikes = PostLikes - 1 WHERE PostID = :PostID;
            ");
            // bind params and execute
            $stmt->bindParam(":PostID", $_POST["postid"]);
            $stmt->execute();
        }
        // then check if cookie has been set for the user disliking this post, if not create it
        if (!isset($_COOKIE["post_" . $_POST["postid"] . "_dislike"])) {
            // means user hasn't disliked the post yet
            // set cookie for 1 year
            setcookie("post_" . $_POST["postid"] . "_dislike", "true", time() + (31536000), "/");
            // update post dislikes in db
            $stmt = $conn->prepare("
            UPDATE tblposts SET PostDislikes = PostDislikes + 1 WHERE PostID = :PostID;
            ");
            // bind params and execute
            $stmt->bindParam(":PostID", $_POST["postid"]);
            $stmt->execute();
        }
        // else check if user has already disliked the post or not
        else {
            // means user has already disliked the post, so remove the dislike
            // delete cookie
            setcookie("post_" . $_POST["postid"] . "_dislike", "", time() - 3600, "/");
            // update post dislikes in db
            $stmt = $conn->prepare("
            UPDATE tblposts SET PostDislikes = PostDislikes - 1 WHERE PostID = :PostID;
            ");
            // bind params and execute
            $stmt->bindParam(":PostID", $_POST["postid"]);
            $stmt->execute();
        }
    }
?>