<?php
require_once("../server.php");

$post_id = $_POST['query'];

$query = "SELECT p.id AS 'post id', phc.comment_id AS 'comment id',
c.content AS 'comment content', uac.username AS 'who add', c.timestamp AS 'timestamp', u.profile_img
FROM posts p, post_has_comments phc, comments c, user_add_comments uac, users u
WHERE p.id = phc.post_id AND phc.comment_id = c.id AND c.id = uac.comment_id AND uac.username = u.username
ORDER BY phc.comment_id ASC";

$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result)){
    if ($post_id == $row[0]){
        $timestamp = date("M j, g:i A", strtotime($row[4]));
        if($row[5] === null) $profile_img_src = "assets/images/default_profile.jpeg"; // if user does not have a profile picture set up, use default
        else {
            $profile_img_src = 'data:image/jpg;base64, ' . base64_encode($row[5]);
        }
        
        echo "
        <div style = 'margin-left: 15px;'>
            <img class = 'comment-profile-pic' alt='s' src = '${profile_img_src}'/>
            <div style = 'position: relative; left: 15px; float: left; width: 200px;word-wrap: break-word;'>
                <strong>" . $row[3] . "</strong>
                <div>" . $row[2] . "</div>
                <div class='timestamp'>$timestamp</div>
             </div>
             <div style = 'clear: both;'></div>
        </div>
        <br>";
}
}

?>