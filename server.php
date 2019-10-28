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
$description ="";
$fullname ="";
$image="";

$username_error="";
$password_error = "";
$errors = array(); 


//create users
$query = "CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(100) PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `description` varchar(250)NULL,
  `password` varchar(100) NOT NULL,
  `profile_img` BLOB NULL 
)";

//creat posts
$query2 = "CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `numlikes` int(255) NULL,
  `create_at` TIMESTAMP NOT NULL,
  `caption` varchar(100) NULL
)";

//create likes
$query3 = "CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `create_at` TIMESTAMP NOT NULL
)";

//creat comments
$query4 = "CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `content` varchar(100) NOT NULL,
  `create_at` TIMESTAMP NOT NULL
)";

$result = $db->query($query);
$result2 = $db->query($query2);
$result3 = $db->query($query3);
$result4 = $db->query($query4);


//SIGN UP
if(isset($_POST['signup']) && $_SERVER['REQUEST_METHOD'] == "POST"){
  $username = mysqli_real_escape_string($db, $_POST['name']);
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $description = mysqli_real_escape_string($db, $_POST['description']);
  $password_1 = mysqli_real_escape_string($db, $_POST['pass']);
  $password_2 = mysqli_real_escape_string($db, $_POST['pass2']);
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
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

    $query = "INSERT INTO users (username, name, description, password) 
          VALUES('$username', '$fullname', '$description','$password')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['description'] = $description;
    signUp_profilePicture();
    header('location: index.php');
  }
}

//LOGIN FORM
if(isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == "POST"){
    $username = mysqli_real_escape_string($db, $_POST['name']);
    $password = mysqli_real_escape_string($db, $_POST['pass']);
    if (empty($username)) {
      array_push($errors, "Username is required");
    }
    if (empty($password)) {
      array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {

      $sql = "SELECT description FROM users WHERE username='$username'";
      $result = mysqli_query($db, $sql);
      $row=mysqli_fetch_row($result);

      $query2 = "SELECT profile_img FROM users WHERE username='$username'";
      $result2 = mysqli_query($db, $query2);
      $row2=mysqli_fetch_row($result2);

      
      $password = md5($password);
      $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
      $results = mysqli_query($db, $query);


      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['description'] = $row[0];
        $_SESSION['profile_pricture'] = '<img src="data:image/jpeg;base64,'.base64_encode($row2['0'] ).'" height="250" width="250" />';
        header('location: index.php');
      }else {
        array_push($errors, "Wrong username/password combination");
      }
    }
}


// UPLOAD PROFILE PICTURE ON POP-UP FORM 
if(isset($_POST['insert'])){
  if (file_exists($_FILES["image"]["tmp_name"])){
    $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
    //update the table with new picture
    $query = "UPDATE users 
              SET profile_img = '$file' 
              WHERE username = '".$_SESSION['username']."' ";

    $result = mysqli_query($db, $query);
    $query2 = "SELECT profile_img FROM users WHERE username = '".$_SESSION['username']."' ";
    $result2 = mysqli_query($db, $query2);
    $row = mysqli_fetch_row($result2);


    $_SESSION['profile_pricture'] = '<img src="data:image/jpeg;base64,'.base64_encode($row[0]).'" height="250" width="250" />'; 
    echo '<script>alert("Image Inserted into Database")</script>';  
  }else{
    echo '<script>alert("No Image File Found")</script>';
  }


  
  if(!empty($_POST['user_infor'] )){
    $description = mysqli_real_escape_string($db, $_POST['user_infor']);
    $query = "UPDATE users 
              SET description = '$description' 
              WHERE username = '".$_SESSION['username']."' ";
    $result = mysqli_query($db, $query);
    $query2 = "SELECT description FROM users WHERE username = '".$_SESSION['username']."' ";
    $result2 = mysqli_query($db, $query2);
    $row = mysqli_fetch_row($result2);
    $_SESSION['description'] = $row[0];
  }

}

function signUp_profilePicture(){
  $profile_img = '<img src="assets/images/default_profile.jpeg" height="250" width="250" />';
  $_SESSION['profile_pricture'] = $profile_img; 
  $query = "INSERT INTO users (profile_img)VALUES('$profile_img')";
  mysqli_query($db, $query);
  
}