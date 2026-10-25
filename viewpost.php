<?php
    // starts session and connect to db
    session_start();
    include_once("connection.php");
    
    // check if loginstatus is set, or if it is false
    if (isset($_SESSION["loginstatus"])) {
        if (!$_SESSION["loginstatus"]) {
            header("Location: login.php");
        }
    }
    else {
        header("Location: login.php");
    }

    $stmt = $conn->prepare("
    SELECT * FROM tblposts WHERE PostID=:PostID;
    ");
    $stmt->bindParam(":PostID", $_SESSION["savedpostid"]);
    $stmt->execute();

?>
<!DOCTYPE html>
<html style="height: fit-content; min-height: 100%;">
    <head>
    <title>View Post</title>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
            function submitAnswer() {
                // get answer from input box and correct answer from hidden input
                let useranswer = document.getElementById("answerbox").value;
                let correctanswer = document.getElementById("questionanswer").value;

                // check if they match
                if (Number(useranswer) === Number(correctanswer)) {
                    document.getElementById("answerstatus").innerHTML = "Correct";
                    // unhide answer status alert and change class to alert-success
                    document.getElementById("answerstatusalert").removeAttribute("hidden");
                    document.getElementById("answerstatusalert").classList.remove("alert-danger");
                    document.getElementById("answerstatusalert").classList.add("alert-success");
                }
                else {
                    document.getElementById("answerstatus").innerHTML = "Incorrect";
                    // unhide answer status alert and change class to alert-danger
                    document.getElementById("answerstatusalert").removeAttribute("hidden");
                    document.getElementById("answerstatusalert").classList.remove("alert-success");
                    document.getElementById("answerstatusalert").classList.add("alert-danger");
                }
            }

            function showHint() {
                // show hint alert
                document.getElementById("questionhintalert").removeAttribute("hidden");
                document.getElementById("questionhint").removeAttribute("hidden");
            }
            
            // preview comment in latex when user types in comment box
            function renderComment() {
                let commentpreview = document.getElementById("commentpreview");
                let text = document.getElementById("commentcontent").value;
                
                commentpreview.innerHTML = "\\(" + text + "\\)";
                MathJax.typeset();
            }
        </script>
    </head>
    <body style="height: 100%">
        <nav class="navbar" style="margin: 0; border-bottom: 2px solid #6ac8d9">
            <div class="container-fluid bg-info">
                <div class="navbar-header">
                    <a class="navbar-brand"><h3 style="margin: 0;">CosMaths</h3></a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Homepage</a></li>
                    <li><a href="accountpage.php">Account</a></li>
                    <li><a href="createpost.php">Create Post</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
        <?php
            // fetch post data and display it, if post is a question then also display question, answer box, and show hint button
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo("<div class='row' style='margin: 0; background-color: #dbdbdb; border-bottom: 2px solid #b4b4b4; display: flex; justify-content: center; align-items: center; padding: 10px;'>");
            echo("<h2 style='margin: 0;'>\\(" . $row["PostTitle"] . "\\)</h2>");
            echo("</div>");
            echo("<div class='row' style='display: flex; justify-content: center; align-items: center; padding: 10px;'>");
            echo("<div class='panel panel-default' style='margin-top: 20px; margin-bottom: 20px; width: 80%;'>");
            echo("<div class='panel-body'>");
            echo("<div class='row' style='display: flex; justify-content: center; align-items: center;'>");
            echo("<p style='margin: 0;'>\\(" . $row["PostContent"] . "\\)</p>");
            echo("</div>");
            if ($row["IsQuestion"] == 1) {
                // store question answer in hidden input so it can be checked when user submits answer
                echo("<input type='hidden' id='questionanswer' value='" . $row["QuestionAnswer"] . "'>");
                echo("<p style='margin: 0;'><b>Answer:</b></p>");
                echo("<div style='display: flex; align-items: center;'>");
                echo("<input type='text' id='answerbox'>");
                echo("<button id='submitanswerbutton' onclick='submitAnswer()' class='btn btn-primary' style='margin-left: 6px;'>Submit Answer</button>");
                echo("<button id='hintbutton' onclick='showHint()' class='btn btn-info' style='margin-left: 6px;'>Show Hint</button>");
                echo("</div>");
                echo("<div class='alert' id='answerstatusalert' style='margin-top: 10px; margin-bottom: 0;' hidden>");
                echo("<strong id='answerstatus'></strong>");
                echo("</div>");
                echo("<div class='alert alert-info' id='questionhintalert' style='margin-top: 10px; margin-bottom: 0;' hidden>");
                echo("<strong id='questionhint' hidden>\\(" . $row["QuestionHint"] . "\\)</strong>");
                echo("</div>");
            }
            // echo like button, dislike button, and date
            echo("<form action='processpostreaction.php' method='post' class='form-inline;' style='margin-top: 10px;'>");
            echo("<input type='hidden' name='postid' value='" . $row["PostID"] . "'>");
            echo("<button type='submit' id='like' name='reaction' value='like' class='btn btn-success' style='padding: 6px;'><span class='glyphicon glyphicon-thumbs-up' style='font-size: 16px;'></span></button>");
            echo("<label for='like' style='margin-left: 6px; margin-right: 6px'>" . $row["PostLikes"] . "</label>");
            echo("<button type='submit' id='dislike' name='reaction' value='dislike' class='btn btn-danger' style='padding: 6px;'><span class='glyphicon glyphicon-thumbs-down' style='font-size: 16px;'></span></button>");
            echo("<label for='dislike' style='margin-left: 6px; margin-right: 6px'>" . $row["PostDislikes"] . "</label>");
            echo("</form>");
            echo("<p style='margin-bottom: 0; margin-top: 10px;'>Date: " . $row["PostTime"] . "</p>");
            echo("</div>");
            echo("</div>");
            echo("</div>");
        ?>
        <div class="row" style="margin: 0; background-color: #dbdbdb; border-bottom: 2px solid #b4b4b4; border-top: 2px solid #b4b4b4; display: flex; justify-content: center; align-items: center; padding: 10px;">
            <h2 style="margin: 0;">Comments</h2>
        </div>
        <form action="processcomment.php" method="post">
            <label for="commentcontent">Type comment:</label><br>
            <textarea id="commentcontent" name="commentcontent" rows="4" cols="50" oninput="renderComment()"></textarea><br>
            <?php
                // display error message if it is set
                if (isset($_SESSION["commentstatus"])) {
                    if (!$_SESSION["commentstatus"]) {
                        echo("<p>Comment error: " . $_SESSION["commenterrormsg"] . "</p>");
                    }
                }
            ?>
            <p>Preview:</p>
            <p id="commentpreview"></p>
            <input type="submit" value="Submit Comment">
        </form>
        <?php
            // fetch all comments for the post in order of most likes to least likes
            $stmt = $conn->prepare("
            SELECT * FROM tblcomments WHERE PostID=:PostID ORDER BY CommentLikes DESC;
            ");
            $stmt->bindParam(":PostID", $_SESSION["savedpostid"]);
            $stmt->execute();

            // display username of user who posted comment, likes, dislikes, and comment time
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // fetch username of user who posted comment from tblusers using UserID
                $stmt2 = $conn->prepare("
                SELECT * FROM tblusers WHERE UserID=:UserID;
                ");
                $stmt2->bindParam(":UserID", $row["UserID"]);
                $stmt2->execute();
                $user = $stmt2->fetch(PDO::FETCH_ASSOC);
                echo("<p><b>" . $user["Username"] . ":</b> \\(" . $row["CommentContent"] . "\\)</p>");
                echo("<p>Likes: " . $row["CommentLikes"] . "</p>");
                echo("<p>Dislikes: " . $row["CommentDislikes"] . "</p>");
                echo("<p>Date: " . $row["CommentTime"] . "</p>");
                // add form for like and dislike butttons
                echo("<form action='processcommentreaction.php' method='post'>");
                echo("<input type='hidden' name='commentid' value='" . $row["CommentID"] . "'>");
                echo("<button type='submit' name='reaction' value='like'>Like</button>");
                echo("<button type='submit' name='reaction' value='dislike'>Dislike</button>");
                echo("</form>");
                echo("<br>");
            }
        ?>
    </body>
</html>