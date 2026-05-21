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
                console.log("user typed");
                // select title preview box and text user typed in
                let titlepreview = document.getElementById("titlepreview");
                let text = document.getElementById("title").value;
                
                // add text to preview box, it needs \(\) around it
                titlepreview.innerHTML = "\\(" + text + "\\)";
                // call mathjax function to render text
                MathJax.typeset();
            }
            // repeat for other input boxes, same as rendertitle
            function renderTitle() {
                console.log("user typed");
                // select title preview box and text user typed in
                let titlepreview = document.getElementById("titlepreview");
                let text = document.getElementById("title").value;
                
                // add text to preview box, it needs \(\) around it
                titlepreview.innerHTML = "\\(" + text + "\\)";
                // call mathjax function to render text
                MathJax.typeset();
            }
        </script>
    </head>
    <body>
        <h1>Create post</h1>
        <form method="post" action="processcreatepost.php">
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
    </body>
</html>
