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
    echo "Connected successfully to database<br><br>";
}

$sql = "SELECT name FROM people";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  echo "Names from database:<br>";
  while($row = $result->fetch_assoc()) {
      echo $row["name"] . "<br>";
  }
} else {
    echo "0 results";
}

$conn->close();


phpinfo();
?>