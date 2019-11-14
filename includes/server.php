<?php

require_once("config.php");
session_start();

// Login to to the database
$db = mysqli_connect($servername, $username, $password, $dbname);
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

$username = "";
$password = "";
$description ="";
$first_name ="";
$last_name = "";
$image="";

$username_error="";
$password_error = "";
$errors = array(); 

//----------------------------------------------------------------------
// CREATING TABLES
//----------------------------------------------------------------------

// The queries for the users, posts, and create tables. 
$users_table = "CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(100) PRIMARY KEY,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `description` varchar(250)NULL,
  `password` varchar(100) NOT NULL,
  `profile_img` BLOB NULL 
)";

$posts_table = "CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT(8) NOT NULL AUTO_INCREMENT,
  `likes` INT(8) DEFAULT 0,
  `timestamp` DATETIME NOT NULL,
  `caption` VARCHAR(1000) NULL,
  `post_image` LONGBLOB NOT NULL, 
  PRIMARY KEY (id)
)";
	
$create_table = "CREATE TABLE IF NOT EXISTS `create` (
  `username` VARCHAR(100), 
  `id` INT(8)
)"; 

// Creates the users, posts, and create tables in the database. 
$queries = array($users_table, $posts_table, $create_table); 

foreach ($queries as $query) {
  $db -> query($query);
}

//----------------------------------------------------------------------
// SIGN UP
//----------------------------------------------------------------------
if(isset($_POST['signup']) && $_SERVER['REQUEST_METHOD'] == "POST"){

  $username = mysqli_real_escape_string($db, $_POST['username']);
  $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
  $password_1 = mysqli_real_escape_string($db, $_POST['pass']);
  $password_2 = mysqli_real_escape_string($db, $_POST['pass2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($first_name)) { array_push($errors, "First Name is required"); }
  if (empty($last_name)) { array_push($errors, "Last Name is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if (empty($password_2)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
    array_push($errors, "The two passwords do not match");
    header('location: signup.php');
  }

  // first check the database to make sure 
  // a user does not already exist with the same username
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

    $query = "INSERT INTO users (username, first_name, last_name, password) 
          VALUES('$username', '$first_name', '$last_name', '$password')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    signUp_profilePicture();
    header('location: index.php');
  }
}

//----------------------------------------------------------------------
// LOGIN FORM
//----------------------------------------------------------------------
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

      $sql = "SELECT description, profile_img FROM users WHERE username='$username'";
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_row($result);

      $query2 = "SELECT profile_img FROM users WHERE username='$username'";
      $result2 = mysqli_query($db, $query2);
      $row2 = mysqli_fetch_row($result2);

      $password = md5($password);
      $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
      $results = mysqli_query($db, $query);
      $user_info = $results -> fetch_assoc();

      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['description'] = $row[0];
        
        $_SESSION['first_name'] =  $user_info["first_name"]; 
        $_SESSION['last_name'] = $user_info["last_name"]; 
        
        if($row2[0] === null) { // if no profile pic in database
          $profile_img = '<img src="assets/images/default_profile.jpeg" height="250" width="250" />';
          $_SESSION['profile_picture'] = $profile_img;
        } 
        else {
          $_SESSION['profile_picture'] = '<img src="data:image/jpeg;base64,'.base64_encode($row2[0]).'" height="250" width="250" />';
        }
        header('location: index.php');
        }
     else {
       array_push($errors, "Wrong username/password combination");
     }
  }
}

//----------------------------------------------------------------------
// UPLOAD PROFILE PICTURE ON POP-UP FORM 
//----------------------------------------------------------------------
if(isset($_POST['insert'])) {
    
  if (file_exists($_FILES["image"]["tmp_name"])) {
      
    $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
      
    //update the table with new picture
    $query = "UPDATE users 
              SET profile_img = '$file' 
              WHERE username = '".$_SESSION['username']."' ";

    $result = mysqli_query($db, $query);
    $query2 = "SELECT profile_img FROM users WHERE username = '".$_SESSION['username']."' ";
    $result2 = mysqli_query($db, $query2);
    $row = mysqli_fetch_row($result2);

    $_SESSION['profile_picture'] = '<img src="data:image/jpeg;base64,'.base64_encode($row[0]).'" height="250" width="250" />'; 
    // echo '<script>alert("Image Inserted into Database")</script>';  
  }
  else {
    // echo '<script>alert("No Image File Found")</script>';
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
  $_SESSION['profile_picture'] = $profile_img;
}

//----------------------------------------------------------------------
// CREATE POST
//----------------------------------------------------------------------
if (isset($_POST["create-post"])) {

	// Only uploads if a file exists. 
	if (file_exists($_FILES["post-image"]["tmp_name"])) {
		
	  // Retrieves the image file and caption. 
	  $file = addslashes(file_get_contents($_FILES["post-image"]["tmp_name"]));
	  $caption = $_POST["post-caption"]; 
		
	  // INSERT INTO POSTS TABLE
	  $query = "INSERT INTO posts(timestamp, caption, post_image) 
		        VALUES (NOW(), '$caption', '$file')"; 
	  $db -> query($query); 
      
      // Retrieves the username of the current user
      $username = $_SESSION["username"]; 
      
      // Retrieves the id of the last post created
      $result = mysqli_query($db, "SELECT id FROM posts ORDER BY id DESC LIMIT 1");      
      $last_post = mysqli_fetch_row($result);
      $post_id = $last_post[0]; 
      
      // INSERT INTO CREATE TABLE
      $db -> query("INSERT INTO `create` (`username`, `id`) VALUES ('$username', '$post_id')"); 
      // echo "Username: $username<br> ID: $post_id <br>"; 
	}
}

?>
