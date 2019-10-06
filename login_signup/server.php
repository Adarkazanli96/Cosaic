<?php

require_once "config.php";
session_start();

//login to database
$db = mysqli_connect($servername, $username, $password, $dbname);
//if ($conn->connect_error) die($conn->connect_error);
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}
$username = "";
$password = "";
$email = "";

$username_error="";
$password_error = "";
$errors = array(); 


//create users
$query = "CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
)";

$result = $db->query($query);

//SIGN UP
if(isset($_POST['signup']) && $_SERVER['REQUEST_METHOD'] == "POST"){
//if(isset($_POST['signup'])){
  echo "signup success";
  $username = mysqli_real_escape_string($db, $_POST['name']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['pass']);
  $password_2 = mysqli_real_escape_string($db, $_POST['pass2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if (empty($password_2)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
    array_push($errors, "The two passwords do not match");
    header('location: signup.php');
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
      header('location: signup.php');
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $password = md5($password_1);//encrypt the password before saving in the database

    $query = "INSERT INTO users (username, email, password) 
          VALUES('$username', '$email', '$password')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    header('location: login.php');
  }
}

//LOGIN FORM
if(isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == "POST"){
//if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($db, $_POST['name']);
    $password = mysqli_real_escape_string($db, $_POST['pass']);

    if (empty($username)) {
      array_push($errors, "Username is required");
    }
    if (empty($password)) {
      array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {

      $password = md5($password);
      $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
      $results = mysqli_query($db, $query);
      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: homepage.php');
      }else {
        array_push($errors, "Wrong username/password combination");
      }
    }
  }
?>