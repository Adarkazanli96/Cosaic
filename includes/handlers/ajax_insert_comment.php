<?php
require_once("../server.php");

$input = mysqli_real_escape_string($db, $_POST['comment-content']);
$post_id = $_POST['hidden-input-post-id'];
$current_user = $_SESSION["username"];

$query = "INSERT INTO comments(id, timestamp, content) VALUES (NULL, NOW(), '$input')";
mysqli_query($db, $query);

// get the comment id
$comment_id = mysqli_insert_id($db);

$query = "INSERT INTO user_add_comments(comment_id, username) VALUES ('$comment_id', '$current_user')";
mysqli_query($db, $query);

$query = "INSERT INTO post_has_comments(post_id, comment_id) VALUES ('$post_id', '$comment_id')";
mysqli_query($db, $query);

echo $_POST['hidden-input-post-id'];

?>