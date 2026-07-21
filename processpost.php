<?php
    // remove htmlspecialchars from input data to prevent injection
    //array_map("htmlspecialchars", $_POST);
    
    // connect to db
    include_once("connection.php");
    // start session so session variables can be used
    session_start();
    // redirect back to createpost.php
    header("Location: createpost.php");

    // default create post status to true
    $_SESSION["createpoststatus"] = true;
    $_SESSION["createposterrormsg"] = "";

    // switch statement to go through all cases of invalid information
    switch(true) {
        // checking if inputs left empty
        case !$_POST["title"]:
            $_SESSION["createposterrormsg"] = "Post must have a title";
            $_SESSION["createpoststatus"] = false;
            break;
        case !$_POST["content"]:
            $_SESSION["createposterrormsg"] = "Post must have content";
            $_SESSION["createpoststatus"] = false;
            break;
        case !$_POST["questionanswer"] && $_POST["isquestion"] == 1:
            $_SESSION["createposterrormsg"] = "Question must have an answer";
            $_SESSION["createpoststatus"] = false;
            break;
        case !$_POST["questionhint"] && $_POST["isquestion"] == 1:
            $_SESSION["createposterrormsg"] = "Question must have a hint";
            $_SESSION["createpoststatus"] = false;
            break;
        case !$_POST["posttopics"]:
            $_SESSION["createposterrormsg"] = "Post must have topic(s)";
            $_SESSION["createpoststatus"] = false;
            break;
        // content too long
        case strlen($_POST["content"]) > 1000:
            // set error message, create post status and break
            $_SESSION["createposterrormsg"] = "Content exceeds 1000 characters";
            $_SESSION["createpoststatus"] = false;
            break;
        // title too long
        case strlen($_POST["title"]) > 300:
            // same as previous case, but different error msg
            $_SESSION["createposterrormsg"] = "Title exceeds 300 characters";
            $_SESSION["createpoststatus"] = false;
            break;
        // checks if the question hint is too long, only if the post is a question
        case $_POST["isquestion"] == 1 && strlen($_POST["questionhint"]) > 300:
            $_SESSION["createposterrormsg"] = "Question hint exceeds 300 characters";
            $_SESSION["createpoststatus"] = false;
            break;
        // checks if question answer is a number
        case $_POST["isquestion"] == 1 && !is_numeric($_POST["questionanswer"]):
            $_SESSION["createposterrormsg"] = "Question answer must be a number with 10 digits in total, including 2 decimal digits";
            $_SESSION["createpoststatus"] = false;
        // checks if question answer is a number, and it is the correct length
        case $_POST["isquestion"] == 1 && is_numeric($_POST["questionanswer"]):
            // when made into an int, should have 8 digits, must check if the answer is 0 first
            if (!floatval($_POST["questionanswer"]) == 0) {
                // use log base 10 to find number of digits
                if (floor(log10(abs(floatval($_POST["questionanswer"]))) + 1) > 8) {
                    // if answer is too long
                    $_SESSION["createposterrormsg"] = "Question answer is too long, must be 10 digits in total, including 2 decimal digits";
                    $_SESSION["createpoststatus"] = false;
                }
            }
    }
    // if no errors, then can add to tblposts
    if ($_SESSION["createpoststatus"]) {
        // insert to tblposts
        $stmt = $conn->prepare("
        INSERT INTO tblposts
        (PostID, UserID, PostContent, PostTitle, PostTime, PostLikes, PostDislikes, IsQuestion, QuestionAnswer, QuestionHint)
        VALUES
        (NULL, :UserID, :PostContent, :PostTitle, CURRENT_TIMESTAMP(), 0, 0, :IsQuestion, :QuestionAnswer, :QuestionHint)
        ");
        // bind params and execute
        $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
        $stmt->bindParam(":PostContent", $_POST["content"]);
        $stmt->bindParam(":PostTitle", $_POST["title"]);
        // if post is a question then set answer and hint, else they are null
        if ($_POST["isquestion"] == 1) {
            $stmt->bindParam(":IsQuestion", $_POST["isquestion"]);
            $stmt->bindParam(":QuestionAnswer", $_POST["questionanswer"]);
            $stmt->bindParam(":QuestionHint", $_POST["questionhint"]);
        }
        else {
            // set to null, isquestion set to 0
            $isquestion = 0;
            $stmt->bindParam(":IsQuestion", $isquestion);
            $null = NULL;
            $stmt->bindParam(":QuestionAnswer", $null);
            $stmt->bindParam(":QuestionHint", $null);
        }
        $stmt->execute();
        // loop through each topic in posttopics and insert to tblpoststags
        $topicnumber = 0;
        foreach ($_POST["posttopics"] as $key => $topic) {
            // then insert each topic to tblpoststags, selects max postid, which is the post just created
            $stmt = $conn->prepare("
            INSERT INTO tblpoststags
            (PostID, TopicNumber, TopicID)
            VALUES
            ((SELECT MAX(PostID) FROM tblposts WHERE UserID = :UserID), :TopicNumber, :TopicID);
            ");
            $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
            $stmt->bindParam(":TopicNumber", $topicnumber);
            $stmt->bindParam(":TopicID", $topic);
            $stmt->execute();
            // increment topicnumber by 1 for next tag (if applicable)
            $topicnumber++;
        }
    }
?>