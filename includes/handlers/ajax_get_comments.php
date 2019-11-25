<?php
require_once("../server.php");

$post_id = $_POST['query'];

$query = "SELECT p.id AS 'post id', phc.comment_id AS 'comment id',
c.content AS 'comment content', uac.username AS 'who add', c.timestamp AS 'timestamp'
FROM posts p, post_has_comments phc, comments c, user_add_comments uac
WHERE p.id = phc.post_id AND phc.comment_id = c.id AND c.id = uac.comment_id
ORDER BY phc.comment_id ASC";

$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result)){
    if ($post_id == $row[0]){
        $timestamp = date("M j, g:i A", strtotime($row[4])); 
    echo "<div style = 'padding: 5px;'>
    <strong>" . $row[3] . "</strong> : " . $row[2] . "<br>
    <p class='timestamp'>$timestamp</p>
    </div>
    <br>";
}
}

?>