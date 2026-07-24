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
                if (useranswer == correctanswer) {
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
        </script>
    </head>
    <body>
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

        ?>
    </body>
</html>