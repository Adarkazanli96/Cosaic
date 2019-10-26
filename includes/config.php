<?php
  $servername = "127.0.0.1";
  $username = "root";
  $password = "password";
  $dbname = "mydb";

  //login to database
  $con = mysqli_connect($servername, $username, $password, $dbname);
  if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
?>