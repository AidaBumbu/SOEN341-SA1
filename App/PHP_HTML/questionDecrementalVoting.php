<?php
if (!isset($_SESSION)) {
    session_start();
}

include_once('sql_connector.php');

$id = $_POST['value'];
$userId = $_SESSION['user_id'];
$result = $db->query("select * from questions  where question_id='$id'");
$row = $result->fetch_assoc();
$result = $db->query("SELECT count(1) from question_userdislikes where user_id='$user_id' AND question_id='$id'");
$isDisliked = mysqli_fetch_array($result);
$result = $db->query("SELECT count(1) from question_userlikes where user_id='$user_id' AND question_id='$id'");
$isLiked = mysqli_fetch_array($result);
if ($isDisliked[0] == 0 && $isLiked[0] == 0) {
    $valueAfterUpdate = $row['question_downvotes'] + 1;
    $db->query("update questions set question_downvotes='$valueAfterUpdate' where question_id='$id'");
    $db->query("INSERT INTO question_userdislikes (question_id, user_id) VALUES ('$id','$userId')");
} else if ($isDisliked[0] == 0) {
    $valueAfterUpdate = $row['question_downvotes'] + 1;
    $valueAfterUpdatelik = $row['question_upvotes'] - 1;
    $db->query("update questions set question_downvotes='$valueAfterUpdate' where question_id='$id'");
    $db->query("update questions set question_upvotes='$valueAfterUpdatelik' where question_id='$id'");
    $db->query("INSERT INTO question_userdislikes (question_id, user_id) VALUES ('$id','$userId')");
    $db->query("DELETE FROM question_userliked WHERE question_id='$id' AND user_id='$userId'");
} else {
    $valueAfterUpdate = $row['question_downvotes'] - 1;
    $db->query("update questions set question_downvotes='$valueAfterUpdate' where question_id='$id'");
    $db->query("DELETE FROM question_userdislikes WHERE question_id='$id' AND user_id='$userId'");
}
?>