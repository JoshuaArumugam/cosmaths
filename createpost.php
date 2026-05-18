<!DOCTYPE html>
<html>
    <head>
        <title>Create Post</title>
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
        </script>
    </head>
    <body>
        <h1>Create post</h1>
        <form method="post" action="processcreatepost.php">
            <label for="title"><b>Title:</b></label><br>
            <input type="text" id="title" name="title"><br>
            <p>Preview:</p>
            <p id="titlepreview"></p>
            <label for="content"><b>Content:</b></label><br>
            <input type="text" id="content" name="content"><br>
            <p>Preview:</p>
            <p id="contentpreview"></p>
            <label for="isquestion"><b>Is the post a question? </b></label>
            <input type="checkbox" id="isquestion" onclick="showQuestionInputs()" name="isquestion" value="1"><br><br>
            <label hidden class="question" for="questionanswer"><b>Question answer:</b></label><br hidden class="question">
            <input hidden class="question" type="text" id="questionanswer" name="questionanswer"><br hidden class="question">
            <label hidden class="question" for="questionhint"><b>Question hint:</b></label><br hidden class="question">
            <input hidden class="question" type="text" id="questionhint" name="questionhint"><br hidden class="question">
            <p hidden class="question">Preview:</p>
            <p hidden id="questionhintpreview" class="question"></p><br hidden class="question">
            <input type="submit" value="Post">
        </form>
    </body>
</html>
