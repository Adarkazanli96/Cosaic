<?php 

session_start();

// redirect to signup if user is not signed up
if (isset($_SESSION['username'])) {
    echo "Welcome to the member's area, " . $_SESSION['username'] . "!";
  } else {
    header('location: signup.php');
  }
?>