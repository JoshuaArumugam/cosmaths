<?php
    // connect to db
    include_once('connection.php');

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
<html style="height: 100%;">
    <head>
        <title>Home page</title>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
        <div class="container" style="margin: 0; padding: 0; height: 100%;">
            <div class="col-sm-3" style="padding-top: 25px; height: 100%; background-color: #f2f2f2; border-right: 2px solid #dbdbdb;">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="processsearch.php" method="post" class="form-inline">
                            <div class="form-group">
                                <input type="text" name="searchquery" placeholder="Search..." class="form-control" style="width: 150px;">
                                <input type="submit" value="Search" class="btn btn-default">
                            </div>
                            <br><br>
                        </form>
                        <form action="processsearchfilters.php" method="post">
                            <label for="sortby"><b>Sort by:</b></label>
                            <select name="sortby" id="sortby" class="form-control">
                                <option value="mostlikes">Most Likes</option>
                                <option value="leastlikes">Least Likes</option>
                                <option value="mostdislikes">Most Dislikes</option>
                                <option value="leastdislikes">Least Dislikes</option>
                                <option value="newest">Newest</option>
                                <option value="oldest">Oldest</option>
                            </select>
                            <br>
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
                                    echo("<div class='checkbox'><label><input type='checkbox' value='1' name='" . $row["TopicName"] . "' id='" . $row["TopicName"] . "'>" . $row["TopicName"] . "</label></div>");
                                }
                            ?>
                            <br>
                            <p><b>Post type:</b></p>
                            <div class="checkbox">
                                <label><input type="checkbox" name="helprequestlesson" value="1" id="helprequestlesson">Help Request/Lesson</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="helprequestlesson" value="1" id="helprequestlesson">Question</label>
                            </div>
                            <input type="submit" value="Submit" class="btn btn-default">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-9" style="padding-top: 25px;">
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
                        // select all posts where search query is in post title, content or username
                        // make the fields lowercase so it doesn't matter if the text in the post is uppercase or not
                        $stmt = $conn->prepare("
                        SELECT * FROM tblposts WHERE LOWER(PostContent) LIKE CONCAT('%', :searchquery, '%')
                        OR LOWER(PostTitle) LIKE CONCAT('%', :searchquery, '%')
                        OR UserID IN (SELECT UserID FROM tblusers WHERE LOWER(Username) LIKE CONCAT('%', :searchquery, '%'));
                        ");
                        // bind params and execute
                        $stmt->bindParam(":searchquery", $_SESSION["searchquery"]);
                        $stmt->execute();

                        // loop through all returned posts and display all the post info
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

                        // set madesearch and searchquery back to 0 and "" respectively
                        $_SESSION["madesearch"] = 0;
                        $_SESSION["searchquery"] = "";
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
            </div>
        </div>
    </body>
</html>