<?php

include('includes/header.php'); // include navigation bar
include("includes/classes/User.php");

?>



    <?php

    require_once("includes/config.php"); // get database object
    
    $user_obj = new User($con, $_SESSION['username']);
    echo "Welcome to the member's area, " . $user_obj->getFirstAndLastName() . "!";
    ?>

</body>
</html>