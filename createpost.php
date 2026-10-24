<?php
    // start session
    session_start();
    // check if loginstatus is set, or if it is false
     if (isset($_SESSION["loginstatus"])) {
        if (!$_SESSION["loginstatus"]) {
            header("Location: login.php");
        }
     }
     else {
        header("Location: login.php");
     }
?>
<!DOCTYPE html>
<html style="height: fit-content; min-height: 100%;">
    <head>
        <title>Create Post</title>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
                        // add form-control class back to last two input boxes
                        if (i == 1 || i == 3) {
                            elements[i].classList.add("form-control");
                        }
                    }
                }
                else {
                    // loop through elements and show them
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].setAttribute("hidden", "");
                        // remove form-control class from last two input boxes
                        if (i == 1 || i == 3) {
                            elements[i].classList.remove("form-control");
                        }
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
    <body style="height: 100%;">
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
        <div class="container-fluid" style="margin: 0; padding: 0; height: fit-content; min-height: 100%; width: 100%;">
            <div class="row" style="display: flex; align-items: stretch; margin: 0;">
                <div class="col-sm-8" style="min-height: calc(100vh - 52.5px); padding: 0; border-right: 2px solid #dbdbdb;">
                    <div class="row" style="margin: 0; border-bottom: 2px solid #b4b4b4; padding: 12px; display: flex; justify-content: center; align-items: center; background-color: #f5f5f5;">
                        <h2 style="margin: 0;">Create Post</h2>
                    </div>
                    <div class="row" style="margin: 0; padding: 12px; display: flex; justify-content: center; align-items: start; height: calc(100% - 59px);">
                        <div class="panel panel-default" style="width: 80%;">
                            <div class="panel-body">
                                <form method="post" action="processpost.php">
                                    <div class="form-group">
                                        <!-- title input box + preview for it -->
                                        <label for="title"><b>Title:</b></label>
                                        <!-- when user types, runs function to display text in preview box -->
                                        <textarea id="title" name="title" oninput="renderTitle()" class="form-control"></textarea>
                                        <p>Preview:</p>
                                        <p id="titlepreview"></p>
                                    </div>
                                    <div class="form-group">
                                        <!-- post content input box + preview for it -->
                                        <label for="content"><b>Content:</b></label>
                                        <textarea id="content" name="content" oninput="renderContent()" class="form-control"></textarea>
                                        <p>Preview:</p>
                                        <p id="contentpreview"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="posttopics"><b>Select topics for post:</b></label>
                                        <select name="posttopics[]" id="posttopics" multiple="multiple" class="form-control">
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
                                        </select>
                                    </div>
                                    <div class="form-group" style="display: flex; align-items: center;">
                                        <label for="isquestion" style="margin-bottom: 0;"><b>Is the post a question? </b></label>
                                        <!-- when checkbox clicked, runs function to hide/show relevant elements -->
                                        <input type="checkbox" id="isquestion" onclick="showQuestionInputs()" name="isquestion" value="1" style="margin-top: 0; margin-left: 4px;">
                                    </div>
                                    <div class="form-group">
                                        <!-- question answer, hint input boxes and preview for the hint -->
                                        <label hidden class="question" for="questionanswer"><b>Question answer:</b></label>
                                        <input hidden class="question" type="text" id="questionanswer" name="questionanswer">
                                    </div>
                                    <div class="form-group">
                                        <label hidden class="question" for="questionhint"><b>Question hint:</b></label>
                                        <input hidden class="question" type="text" id="questionhint" name="questionhint" oninput="renderHint()">
                                        <p hidden class="question">Preview:</p>
                                        <p hidden id="questionhintpreview" class="question"></p>
                                    </div>
                                    <input type="submit" value="Post" class="btn btn-primary form-control">
                                </form>
                            </div>
                        </div>
                    </div>
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
                </div>
                <div class="col-sm-4" style="padding: 0;">
                    <!-- on-screen keyboard for latex -->
                    <div class="row" style="margin: 0; border-bottom: 2px solid #b4b4b4; padding: 12px; display: flex; justify-content: center; align-items: center; background-color: #f5f5f5;">
                        <h2 style="margin: 0;">Keyboard</h2>
                    </div>
                    <div class="row" style="margin: 0; padding: 12px; display: flex; justify-content: center; align-items: start; background-color: #f5f5f5; height: calc(100% - 59px);">
                        <div id="buttonsgrid" style="display: grid; grid-template-columns: repeat(4, 1fr);">
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\frac{}{}')">\(\frac{a}{b}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\sqrt{}')">\(\sqrt{x}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\sum_{}^{}')">\(\sum_{i=1}^{n}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\int_{}^{}dx')">\(\int_{a}^{b}dx\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\lim_{x \\to }')">\(\lim_{x \to c}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\infty')">\(\infty\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\pi')">\(\pi\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\sin(\\theta)')">\(\sin(\theta)\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\cos(\\theta)')">\(\cos(\theta)\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\tan(\\theta)')">\(\tan(\theta)\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('{}^{}')">\(a^{b}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\sqrt[n]{x}')">\(\sqrt[n]{x}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\text{}')">\(\text{Text}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\mathbf{v}')">\(\mathbf{v}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\vec{v}')">\(\vec{v}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\begin{pmatrix}x \\\\y\\end{pmatrix}')">\(\begin{pmatrix}x \\y\end{pmatrix}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\log_{a}{b}')">\(\log_{a}{b}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\ln{a}')">\(\ln{a}\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\lt')">\(\lt\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\le')">\(\le\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\gt')">\(\gt\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\ge')">\(\ge\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\sin^{-1}{x}')">\(\sin^{-1}(x)\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\cos^{-1}{x}')">\(\cos^{-1}(x)\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\tan^{-1}{x}')">\(\tan^{-1}(x)\)</button>
                            <button class="btn btn-default" style="border-radius: 0;" onclick="addToTextBox('\\prod_{i=1}^{n}')">\(\prod_{i=1}^{n}\)</button>
                        </div>
                    </div>
                    <script>
                        // when text box clicked, save the text box in lastusedtextbox variable
                        let lastusedtextbox = null;

                        // select all text boxes
                        let textboxes = document.querySelectorAll("input[type='text'], textarea");
                        // save when clicked
                        textboxes.forEach(function(textbox) {
                            textbox.addEventListener("focus", function() {
                                lastusedtextbox = this;
                            });
                        });
                        
                        // function to add latex to lastusedtextbox when button clicked
                        function addToTextBox(latex) {
                            // check if lastusedtextbox null
                            if (lastusedtextbox != null) {
                                lastusedtextbox.value = lastusedtextbox.value + latex;
                            }
                            // render all text boxes
                            renderTitle()
                            renderContent()
                            renderHint()
                        }
                    </script>
                </div>
            </div>
        </div>
    </body>
</html>
