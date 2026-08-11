<?php
    // starts session and connect to db
    session_start();
    include_once("connection.php");
    $stmt = $conn->prepare("
    SELECT * FROM tblposts WHERE PostID=:PostID;
    ");
    $stmt->bindParam(":PostID", $_SESSION["savedpostid"]);
    $stmt->execute();
?>
<!DOCTYPE html>
<html>
    <head>
    <title>View Post</title>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>
    <script>
            function submitAnswer() {
                // get answer from input box and correct answer from hidden input
                let useranswer = document.getElementById("answerbox").value;
                let correctanswer = document.getElementById("questionanswer").value;

                // check if they match
                if (Number(useranswer) === Number(correctanswer)) {
                    document.getElementById("answerstatus").innerHTML = "Correct";
                }
                else {
                    document.getElementById("answerstatus").innerHTML = "Incorrect";
                }
            }

            function showHint() {
                // show hint paragraph
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
    <body>
        <?php
            // print out cookies to test
            print_r($_COOKIE);
        ?>
        <h1>View Post</h1>
        <?php
            // fetch post data and display it, if post is a question then also display question, answer box, and show hint button
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo("<h4>\\(" . $row["PostTitle"] . "\\)</h4>");
            echo("<p>\\(" . $row["PostContent"] . "\\)</p>");
            if ($row["IsQuestion"] == 1) {
                // store question answer in hidden input so it can be checked when user submits answer
                echo("<input type='hidden' id='questionanswer' value='" . $row["QuestionAnswer"] . "'>");
                echo("<h5>Answer:</h5>");
                echo("<input type='text' id='answerbox'>");
                echo("<p id='answerstatus'></p>");
                echo("<button id='submitanswerbutton' onclick='submitAnswer()'>Submit Answer</button>");
                echo("<button id='hintbutton' onclick='showHint()'>Show Hint</button>");
                echo("<p id='questionhint' hidden>\\(" . $row["QuestionHint"] . "\\)</p>");
            }
            // echo post likes, dislikes, and date
            // also echo like and dislike buttons for the post
            echo("<p>Likes: " . $row["PostLikes"] . "</p>");
            echo("<p>Dislikes: " . $row["PostDislikes"] . "</p>");
            echo("<p>Date: " . $row["PostTime"] . "</p>");
            echo("<form action='processpostreaction.php' method='post'>");
            echo("<input type='hidden' name='postid' value='" . $row["PostID"] . "'>");
            echo("<button type='submit' name='reaction' value='like'>Like</button>");
            echo("<button type='submit' name='reaction' value='dislike'>Dislike</button>");
            echo("</form>");
        ?>
        <h2>Comments</h2>
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
                echo("<p><b>" . $user["Username"] . ":</b> " . $row["CommentContent"] . "</p>");
                echo("<p>Likes: " . $row["CommentLikes"] . "</p>");
                echo("<p>Dislikes: " . $row["CommentDislikes"] . "</p>");
                echo("<p>Date: " . $row["CommentTime"] . "</p>");
                echo("<br>");
            }
        ?>
    </body>
</html>