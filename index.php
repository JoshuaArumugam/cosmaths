<?php
    // connect to db
    include_once('connection.php');

    // start session
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home page</title>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>
    </head>
    <body>
        <form action="processsearch.php" method="post">
            <input type="text" name="searchquery" placeholder="Search...">
            <input type="submit" value="Search">
        </form>
        <form action="processsearchfilters.php" method="post">
            <label for="sortby"><b>Sort by:</b></label>
            <select name="sortby" id="sortby">
                <option value="mostlikes">Most Likes</option>
                <option value="leastlikes">Least Likes</option>
                <option value="mostdislikes">Most Dislikes</option>
                <option value="leastdislikes">Least Dislikes</option>
                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
            </select>
            <p><b>Select topics:</b></p>
            <?php
                // fetch all topics
                $stmt = $conn->prepare("
                SELECT * FROM tbltopiclabels;
                ");
                $stmt->execute();

                // loop through each returned record and create checkbox for each
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // create checkbox
                    echo("<input type='checkbox' value='1' name='" . $row["TopicName"] . "' id='" . $row["TopicName"] . ">");
                    echo("<label for='" . $row["TopicName"] . "'>" . $row["TopicName"] . "</label><br>");
                }
            ?>
            <p><b>Post type:</b></p>
            <input type="checkbox" name="helprequestlesson" value="1" id="helprequestlesson">
            <label for="helprequestlesson">Help Request/Lesson</label><br>
            <input type="checkbox" name="question" value="1" id="question">
            <label for="question">Question</label><br>
            <input type="submit" value="Submit">
        </form>
        <?php
            // check if madesearchquery and madesearch are not 1
            if ($_SESSION["madesearchquery"] != 1 && $_SESSION["madesearch"] != 1) {
                // fetch all posts and sort by most likes
                $stmt = $conn->prepare("
                SELECT * FROM tblposts ORDER BY PostLikes DESC;
                ");
                $stmt->execute();

                // loop through records and echo post title, content, likes, dislikes, comments, and date
                // add \\(\\) around latex so it gets rendered by mathjax
                // everything is put inside a form element so postid can be posted to savepostid.php when user clicks anywhere on the post
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // count how many comments on each post
                    $stmt2 = $conn->prepare("
                    SELECT COUNT(*) FROM tblcomments WHERE PostID = :PostID;
                    ");

                    // bind postid and execute
                    $stmt2->bindParam(":PostID", $row["PostID"]);
                    $stmt2->execute();

                    // get comment count
                    $row1 = $stmt2->fetch(PDO::FETCH_ASSOC);

                    // echo all post info
                    echo("<form action='savepostid.php' method='post' onclick='this.submit()' style='cursor: pointer;'>");
                    echo("<input type='hidden' name='PostID' value='" . $row["PostID"] . "'>");
                    echo("<h2>\\(" . $row["PostTitle"] . "\\)</h2>");
                    echo("<p>\\(" . $row["PostContent"] . "\\)</p>");
                    echo("<p><b>Likes:</b> " . $row["PostLikes"] . "</p>");
                    echo("<p><b>Dislikes:</b> " . $row["PostDislikes"] . "</p>");
                    echo("<p><b>Comments:</b> " . $row1["COUNT(*)"] . "</p>");
                    echo("<p><b>Date:</b> " . $row["PostTime"] . "</p>");
                    echo("<br>");
                    echo("</form>");
                }
            }
            // check if madesearch is 1
            elseif ($_SESSION["madesearch"] == 1) {
                // loop through all returned posts and display all the post info
                // add \\(\\) around latex so it gets rendered by mathjax
                // everything is put inside a form element so postid can be posted to savepostid.php when user clicks anywhere on the post
                while ($row = $_SESSION["searchresults"]->fetch(PDO::FETCH_ASSOC)) {
                    // count how many comments on each post
                    $stmt2 = $conn->prepare("
                    SELECT COUNT(*) FROM tblcomments WHERE PostID = :PostID;
                    ");

                    // bind postid and execute
                    $stmt2->bindParam(":PostID", $row["PostID"]);
                    $stmt2->execute();

                    // get comment count
                    $row1 = $stmt2->fetch(PDO::FETCH_ASSOC);

                    // echo all post info
                    echo("<form action='savepostid.php' method='post' onclick='this.submit()' style='cursor: pointer;'>");
                    echo("<input type='hidden' name='PostID' value='" . $row["PostID"] . "'>");
                    echo("<h2>\\(" . $row["PostTitle"] . "\\)</h2>");
                    echo("<p>\\(" . $row["PostContent"] . "\\)</p>");
                    echo("<p><b>Likes:</b> " . $row["PostLikes"] . "</p>");
                    echo("<p><b>Dislikes:</b> " . $row["PostDislikes"] . "</p>");
                    echo("<p><b>Comments:</b> " . $row1["COUNT(*)"] . "</p>");
                    echo("<p><b>Date:</b> " . $row["PostTime"] . "</p>");
                    echo("<br>");
                    echo("</form>");
                }

                // set madesearch back to 0
                $_SESSION["madesearch"] = 0;
            }
            else {
                // run sql statement and display all returned posts
                $stmt = $conn->prepare($_SESSION["searchstatement"]);
                $stmt->execute();

                // loop through records and echo post title, content, likes, dislikes, comments, and date
                // add \\(\\) around latex so it gets rendered by mathjax
                // everything is put inside a form element so postid can be posted to savepostid.php when user clicks anywhere on the post
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // count how many comments on each post
                    $stmt2 = $conn->prepare("
                    SELECT COUNT(*) FROM tblcomments WHERE PostID = :PostID;
                    ");

                    // bind postid and execute
                    $stmt2->bindParam(":PostID", $row["PostID"]);
                    $stmt2->execute();

                    // get comment count
                    $row1 = $stmt2->fetch(PDO::FETCH_ASSOC);

                    // echo all post info
                    echo("<form action='savepostid.php' method='post' onclick='this.submit()' style='cursor: pointer;'>");
                    echo("<input type='hidden' name='PostID' value='" . $row["PostID"] . "'>");
                    echo("<h2>\\(" . $row["PostTitle"] . "\\)</h2>");
                    echo("<p>\\(" . $row["PostContent"] . "\\)</p>");
                    echo("<p><b>Likes:</b> " . $row["PostLikes"] . "</p>");
                    echo("<p><b>Dislikes:</b> " . $row["PostDislikes"] . "</p>");
                    echo("<p><b>Comments:</b> " . $row1["COUNT(*)"] . "</p>");
                    echo("<p><b>Date:</b> " . $row["PostTime"] . "</p>");
                    echo("<br>");
                    echo("</form>");
                }

                // set madesearchquery back to 0
                $_SESSION["madesearchquery"] = 0;
            }
        ?>
    </body>
</html>