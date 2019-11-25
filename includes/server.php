<?php

require_once("config.php");
session_start();

// Login to to the server
$db = mysqli_connect($servername, $username, $password);
if ($db -> connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Creates a database named 'cosaic' if it does not exists
$db -> query("CREATE DATABASE IF NOT EXISTS $dbname"); 

// Connects to the database. 
$db = mysqli_connect($servername, $username, $password, $dbname);

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

// The queries for every table needed. 
$users_table = "CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(100) PRIMARY KEY,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `description` varchar(250)NULL,
  `password` varchar(100) NOT NULL,
  `profile_img` LONGBLOB NULL 
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
 id int(8), username varchar(100),
 CONSTRAINT FOREIGN KEY (id) REFERENCES posts (id),
 CONSTRAINT FOREIGN KEY (username) REFERENCES users (username)
)"; 

$tagged_in_table = "CREATE TABLE IF NOT EXISTS `tagged_in` (
 id int(8), username varchar(100),
 CONSTRAINT FOREIGN KEY (id) REFERENCES posts (id),
 CONSTRAINT FOREIGN KEY (username) REFERENCES users (username)
)"; 

$likes_table = "CREATE TABLE IF NOT EXISTS `likes` (
  `id` INT(8) NOT NULL AUTO_INCREMENT,
  `timestamp` DATETIME NOT NULL,
  PRIMARY KEY (id)
)";

$post_has_likes_table = "CREATE TABLE IF NOT EXISTS `post_has_likes` (
  post_id int(8), like_id int(8),
  CONSTRAINT FOREIGN KEY (post_id) REFERENCES posts (id),
  CONSTRAINT FOREIGN KEY (like_id) REFERENCES likes (id)
)";

$user_add_likes_table = "CREATE TABLE IF NOT EXISTS `user_add_likes` (
  like_id int(8), username varchar(100),
  CONSTRAINT FOREIGN KEY (like_id) REFERENCES likes (id),
  CONSTRAINT FOREIGN KEY (username) REFERENCES users (username)
)";

$comments_table = "CREATE TABLE IF NOT EXISTS `comments` (
  `id` INT(8) NOT NULL AUTO_INCREMENT,
  `timestamp` DATETIME NOT NULL,
  `content` VARCHAR(1000) NULL,
  PRIMARY KEY (id)
)";

$post_has_comments_table = "CREATE TABLE IF NOT EXISTS `post_has_comments` (
  post_id int(8), comment_id int(8),
  CONSTRAINT FOREIGN KEY (post_id) REFERENCES posts (id),
  CONSTRAINT FOREIGN KEY (comment_id) REFERENCES comments (id)
)";

$user_add_comments_table = "CREATE TABLE IF NOT EXISTS `user_add_comments` (
  comment_id int(8), username varchar(100),
  CONSTRAINT FOREIGN KEY (comment_id) REFERENCES comments (id),
  CONSTRAINT FOREIGN KEY (username) REFERENCES users (username)
)";

// Creates the users, posts, and create tables in the database. 
$queries = array($users_table, 
                 $posts_table, 
                 $create_table, 
                 $tagged_in_table, 
                 $likes_table, 
                 $post_has_likes_table, 
                 $user_add_likes_table,
                 $comments_table, 
                 $post_has_comments_table, 
                 $user_add_comments_table
                ); 

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
    $_SESSION["first_name"] =  $first_name; 
    $_SESSION["last_name"] = $last_name; 
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

      $sql = "SELECT description FROM users WHERE username='$username'";
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_row($result);

      $query2 = "SELECT profile_img FROM users WHERE username='$username'";
      $result2 = mysqli_query($db, $query2);
      $row2 = mysqli_fetch_row($result2);

      $password = md5($password);
      $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
      $results = mysqli_query($db, $query);

      $query3 = "SELECT first_name, last_name FROM users WHERE username='$username'";
      $result3 = mysqli_query($db, $query3);
      $row3 = mysqli_fetch_row($result3);

      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['description'] = $row[0];

        $_SESSION['first_name'] =  $row3[0]; 
        $_SESSION['last_name'] = $row3[1];
        
        
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
    $tagged_users = get_tagged_users($caption);
		
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

      // INSERT INTO TAGGED_IN TABLE
      foreach($tagged_users as $tagged_user){
        $db -> query("INSERT INTO `tagged_in` (`username`, `id`) VALUES ('$tagged_user', '$post_id')"); 
      }
	}
}

//----------------------------------------------------------------------
// TAGS USERS
//----------------------------------------------------------------------
function get_tagged_users($caption){
  $usernames = [];
  $words = explode(" ", $caption);
  
  for($i = 0; $i<sizeof($words); $i++){
    if($words[$i][0] === '@'){ // if the first letter starts with '@'
      array_push($usernames, substr($words[$i],1)); // push to usernames without '@'
      continue;
    }
  }

  return $usernames;
}


//----------------------------------------------------------------------
// LIKE BUTTONS
//----------------------------------------------------------------------
$result = mysqli_query($db, "SELECT id FROM posts");   

while ($row = mysqli_fetch_row($result)) {
  
  $post_id = $row[0]; 
  
  if (isset($_POST["like-button-$post_id"])) {
      
    // Add to likes
    mysqli_query($db, "INSERT INTO likes (id, timestamp) 
                       VALUES (NULL, NOW())"); 

    // Retrieves the like id number. 
    $result = mysqli_query($db, "SELECT id FROM likes ORDER BY id DESC LIMIT 1");      
    $last_like = mysqli_fetch_row($result);
    $like_id = $last_like[0]; 

    // Add to user_add_likes
    $current_user = $_SESSION["username"]; 
    mysqli_query($db, "INSERT INTO user_add_likes (username, like_id) 
                       VALUES ('$current_user', '$like_id')"); 

    // Add to post_has_likes
    mysqli_query($db, "INSERT INTO post_has_likes (post_id, like_id) 
                       VALUES ('$post_id', '$like_id')"); 
  } 
}
//----------------------------------------------------------------------
// ADDING COMMENTS
//----------------------------------------------------------------------
/*if(isset($_POST['post-comment']) && $_SERVER['REQUEST_METHOD'] == "POST"){
  $input = mysqli_real_escape_string($db, $_POST['comment-content']);
  $post_id = $_POST['hidden-input-post-id'];
  $current_user = $_SESSION["username"];

  $query = "INSERT INTO comments(id, timestamp, content) VALUES (NULL, NOW(), '$input')";
  mysqli_query($db, $query);

  // get the comment id
  $comment_id = mysqli_insert_id($db);

  $query = "INSERT INTO user_add_comments(comment_id, username) VALUES ('$comment_id', '$current_user')";
  mysqli_query($db, $query);

  $query = "INSERT INTO post_has_comments(post_id, comment_id) VALUES ('$post_id', '$comment_id')";
  mysqli_query($db, $query);
}*/
?>