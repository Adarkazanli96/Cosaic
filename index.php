<?php

include('includes/header.php'); // include navigation bar
include("includes/classes/User.php");

?>

    <?php

    require_once("includes/server.php"); // get database object
    
    $user_obj = new User($db, $_SESSION['username']);
    echo "Welcome to the member's area, " . $user_obj->getFirstAndLastName() . "!";
    ?>

</body>
</html>