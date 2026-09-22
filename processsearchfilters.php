<?php
    // start session and redirect back to index.php when done
    session_start();
    header("Location: index.php");

    // connect to db
    include_once('connection.php');

    // switch statement to go through all filters
    switch($_POST["sortby"]) {
        case "mostlikes":
            // this will go at the end of the sql statement
            $sortby = "PostLikes DESC";
            break;
        case "leastlikes":
            $sortby = "PostLikes";
            break;
        case "mostdislikes":
            $sortby = "PostDislikes DESC";
            break;
        case "leastdislikes":
            $sortby = "PostDislikes";
            break;
        case "newest":
            $sortby = "PostTime DESC";
            break;
        case "oldest":
            $sortby = "PostTime";
            break;
    }

    // select all topics from tbltopiclabels
    $stmt = $conn->prepare("
    SELECT * FROM tbltopiclabels;
    ");
    $stmt->execute();

    // loop through topic names and check if user is searching for them, if they are then add to topicsearchlist
    $topicsearchlist = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // if topic was selected to be searched for, its value will be 1 in the form
        if ($_POST[$row["TopicName"]] == 1) {
            $topicsearchlist[] = "'" . $row["TopicName"] . "'";
        }
    }

    // check if user is searching for question or other post
    $posttype = "";

    if ($_POST["question"] == 1 && $_POST["helprequestlesson"] == 0) {
        $posttype = "AND IsQuestion = 1 ";
    }
    elseif($_POST["question"] == 0 && $_POST["helprequestlesson"] == 1) {
        $posttype = "AND IsQuestion = 0 ";
    }
    // if both are 0 then any post can be returned, so posttype can be left blank

    // construct sql statement
    // if topicsearchlist is blank, then it doesn't matter what topic the post has
    if (count($topicsearchlist) == 0) {
        $_SESSION["searchstatement"] = "
        SELECT * FROM tblposts WHERE TRUE " . $posttype . "ORDER BY " . $sortby . ";";
    }
    // else select posts which have topics in the topic list
    else {
        // topic names are stored in tbltopiclabels and topics for each post are stored in tblpoststags so i need to use a join
        $_SESSION["searchstatement"] = "
        SELECT * FROM tblposts JOIN tblpoststags ON tblposts.PostID = tblpoststags.PostID
        JOIN tbltopiclabels ON tblpoststags.TopicID = tbltopiclabels.TopicID
        WHERE tbltopiclabels.TopicName IN (" . implode(',', $topicsearchlist) . ") " . $posttype . "ORDER BY " . $sortby . ";";

    }
    
    // set madesearchquery to 1
    $_SESSION["madesearchquery"] = 1;
?>