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
<html style="width: 100%; height: 100%;">
    <head>
        <title>CosMaths Account</title>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@4/tex-mml-chtml.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body style="margin: 0; width: 100%; height: 100%;">
        <nav class="navbar" style="margin: 0; border-bottom: 2px solid #6ac8d9">
            <div class="container-fluid bg-info">
                <div class="navbar-header">
                    <a class="navbar-brand"><h3 style="margin: 0;">CosMaths</h3></a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="login.php">Log-in</a></li>
                    <li><a href="signup.php">Sign-up</a></li>
                </ul>
            </div>
        </nav>
        <div class="row" style="margin: 0; background-color: #dbdbdb; border-bottom: 2px solid#b4b4b4; display: flex; justify-content: center; align-items: center;">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <div class="panel panel-default" style="margin-top: 20px; margin-bottom: 20px;">
                    <div class="panel-heading" style="display: flex; align-items: center; justify-content: center; padding: 0;">
                        <h2>Account Information</h2>
                    </div>
                    <div class="panel-body">
                        <p><b>Username:</b></p>
                        <?php 
                            // echo username in session variable
                            echo($_SESSION["username"]);
                        ?>
                        <p><b>Email:</b></p>
                        <?php 
                            // do same for email
                            echo($_SESSION["email"]);
                        ?>
                        <h3>Interests</h3>
                        <form action="addinterest.php" method="post">
                            <label for="interesttoadd">Select interest to add:</label>
                            <select name="interesttoadd" id="interesttoadd">
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
                            <input type="submit" value="Add interest">
                        </form>
                        <ol>
                            <?php
                                // fetch all user interests
                                $stmt = $conn->prepare("
                                SELECT * FROM tblinterests WHERE UserID=:UserID;
                                ");

                                // bind params and execute stmt
                                $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
                                $stmt->execute();

                                // loop through returned records and add each interest along with the option to remove it

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // fetch name of topic using topic id
                                    $stmt2 = $conn->prepare("
                                    SELECT TopicName FROM tbltopiclabels WHERE TopicID=:TopicID;
                                    ");

                                    // bind params and execute stmt
                                    $stmt2->bindParam(":TopicID", $row["TopicID"]);
                                    $stmt2->execute();

                                    // get returned topic name
                                    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                                    // create li element, and form so that interest can be removed
                                    echo("<li><form action='removeinterest.php' method='post'><input name='topicid' type='hidden' value='" . $row["TopicID"] . "'><label for='submitremove'>". $row2["TopicName"] . " </label><input type='submit' value='Remove' id='submitremove'></form></li>");
                                }
                            ?>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="row" style="margin: 0;">
            <h3>Posts</h3>
            <?php
                // fetch all posts using userid
                $stmt = $conn->prepare("
                SELECT * FROM tblposts WHERE UserID=:UserID;
                ");

                // bind params and execute
                $stmt->bindParam(":UserID", $_SESSION["loggedinid"]);
                $stmt->execute();

                // loop through records and echo post title, content, likes, dislikes, and date
                // add \\(\\) around latex so it gets rendered by mathjax
                // everything is put inside a form element so postid can be posted to savepostid.php when user clicks anywhere on the post
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo("<form action='savepostid.php' method='post' onclick='this.submit()' style='cursor: pointer;'>");
                    echo("<input type='hidden' name='PostID' value='" . $row["PostID"] . "'>");
                    echo("<h4>\\(" . $row["PostTitle"] . "\\)</h4>");
                    echo("<p>\\(" . $row["PostContent"] . "\\)</p>");
                    echo("<p><b>Likes:</b> " . $row["PostLikes"] . "</p>");
                    echo("<p><b>Dislikes:</b> " . $row["PostDislikes"] . "</p>");
                    echo("<p><b>Date:</b> " . $row["PostTime"] . "</p>");
                    echo("<br>");
                    echo("</form>");
                }
            ?>
        </div>
    </body>
</html>
