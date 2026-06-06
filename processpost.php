<?php
    // remove htmlspecialchars from input data to prevent injection
    array_map("htmlspecialchars", $_POST);
    // connect to db
    include_once("connection.php");
    // start session so session variables can be used
    session_start();
    // redirect back to createpost.php
    //header("Location: createpost.php");

    // default create post status to true
    $_SESSION["createpoststatus"] = true;
    $_SESSION["createposterrormsg"] = "";

    // switch statement to go through all cases of invalid information
    switch(true) {
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

    // print out error message and status to check if code works
    print_r($_SESSION["createposterrormsg"]);
?>