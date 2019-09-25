<?php
  $servername = "127.0.0.1";
  $username = "root";
  $password = "password";
  $dbname = "mydb";

  # connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
else{
    echo "connected successfully to database";
}

$conn->close();


phpinfo();
?>