<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create Post</title>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>
        <script>
            // runs everytime clicked
            function showQuestionInputs() {
                // get elements to be hidden
                let elements = document.getElementsByClassName("question");
                
                // check if box checked or unchecked
                if (document.getElementById("isquestion").checked == true) {
                    // loop through elements and hide them
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].removeAttribute("hidden");
                    }
                }
                else {
                    // loop through elements and show them
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].setAttribute("hidden", "");
                    }
                }
            }
            // runs when user types in each box
            function renderTitle() {
                // select title preview box and text user typed in
                let titlepreview = document.getElementById("titlepreview");
                let text = document.getElementById("title").value;
                
                // add text to preview box, it needs \(\) around it
                titlepreview.innerHTML = "\\(" + text + "\\)";
                // call mathjax function to render text
                MathJax.typeset();
            }
            // repeat for other input boxes, same as rendertitle
            function renderContent() {
                let contentpreview = document.getElementById("contentpreview");
                let text = document.getElementById("content").value;
                
                contentpreview.innerHTML = "\\(" + text + "\\)";
                MathJax.typeset();
            }
            // render question hint
            function renderHint() {
                let hintpreview = document.getElementById("questionhintpreview");
                let text = document.getElementById("questionhint").value;
                
                hintpreview.innerHTML = "\\(" + text + "\\)";
                MathJax.typeset();
            }
        </script>
    </head>
    <body>
        <h1>Create post</h1>
        <form method="post" action="processpost.php">
            <!-- title input box + preview for it -->
            <label for="title"><b>Title:</b></label><br>
            <!-- when user types, runs function to display text in preview box -->
            <input type="text" id="title" name="title" oninput="renderTitle()"><br>
            <p>Preview:</p>
            <p id="titlepreview"></p>
            <!-- post content input box + preview for it -->
            <label for="content"><b>Content:</b></label><br>
            <input type="text" id="content" name="content" oninput="renderContent()"><br>
            <p>Preview:</p>
            <p id="contentpreview"></p>
            <label for="posttopics"><b>Select topics for post:</b></label><br>
            <select name="posttopics[]" id="posttopics" multiple="multiple">
                <?php
                    // connect to db and fetch all topics from table
                    include_once('connection.php');

                    $stmt = $conn->prepare("
                    SELECT * FROM tbltopiclabels;
                    ");
                    $stmt->execute();

                    // loop through returned records and add all options to the dropdown list

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // create select option
                        echo("<option value='" . $row["TopicID"] . "'>" . $row["TopicName"] . "</option>");
                    }
                ?>
            </select><br><br>
            <label for="isquestion"><b>Is the post a question? </b></label>
            <!-- when checkbox clicked, runs function to hide/show relevant elements -->
            <input type="checkbox" id="isquestion" onclick="showQuestionInputs()" name="isquestion" value="1"><br><br>
            <!-- question answer, hint input boxes and preview for the hint -->
            <label hidden class="question" for="questionanswer"><b>Question answer:</b></label><br hidden class="question">
            <input hidden class="question" type="text" id="questionanswer" name="questionanswer"><br hidden class="question">
            <label hidden class="question" for="questionhint"><b>Question hint:</b></label><br hidden class="question">
            <input hidden class="question" type="text" id="questionhint" name="questionhint" oninput="renderHint()"><br hidden class="question">
            <p hidden class="question">Preview:</p>
            <p hidden id="questionhintpreview" class="question"></p><br hidden class="question">
            <input type="submit" value="Post">
        </form>
        <?php
            // check create post status to check whether there was a previous attempt
            if (isset($_SESSION["createpoststatus"])) {
                if ($_SESSION["createpoststatus"]) {
                    // success message
                    echo("<p>Post created successfully</p>");
                }
                else {
                    // display error message
                    echo("<p>Post creation error: " . $_SESSION["createposterrormsg"] . "</p>");
                }
            }
        ?>
    </body>
</html>
