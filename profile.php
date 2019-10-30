<?php
include('includes/header.php');
require_once("includes/server.php"); // get database configurations

if(isset($_GET['profile_username'])){
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
    $user_array = mysqli_fetch_array($user_details_query);
}


?>

    <div>This is <?php echo $user_array['first_name'] . " " . $user_array['last_name']; ?>'s profile</div>
</body>

</html>