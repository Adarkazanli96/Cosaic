<?php

include('includes/header.php'); // include navigation bar
include("includes/classes/User.php");

?>



    <?php
    
    echo "Welcome to the member's area, " . $_SESSION['username'] . "!";
    $user_obj = new User($con, $userLoggedIn);
    ?>

</body>
</html>