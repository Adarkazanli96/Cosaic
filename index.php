<?php
session_start();

// redirect to signup if user is not signed up
if (!isset($_SESSION['username'])) {
    header('location: signup.php');
}

require_once ('includes/server.php')
?>

<html>

<head>
    <title>Cosaic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="./assets/images/cosaic_favicon.png">
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" > 
    <link rel="stylesheet" type="text/css" href="./assets/css/profile.css" > 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="main.js" type="text/javascript">></script>
</head>

<body>

<nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <img href="index.php" src='./assets/images/cosaic_navbar_logo.png' alt='like' height="40em">
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
				
                <!-- HOME BUTTON -->
                <li>
                    <a href="index.php" class="current">Home</a>
                </li>
                
                <!-- POST BUTTON -->
                <li>
				  <a onclick="openCreatePostForm()" id = 'create-post' class="current">Post</a>
                </li>
                
                <!-- CREATE POST POPUP -->
			   <div class="form-popup" id="create-post-form">
                    <form class="form-container" method="post" enctype="multipart/form-data">
						
						<label>Image</label>
                        <input type="file" name="post-image" id="post-image" accept="image/*"/>  
						
                        <label>Caption</label>
                        <input type="text" name ="post-caption" placeholder="Insert caption" autocomplete = "off">
                         
                        <input type="submit" name="create-post" id="create-post" value="Post" class="btn btn-info" /> 
                        <button type="button" class="btn cancel" onclick="closeForms()">Close</button>
                    </form>
                </div>
			    <!-- EDIT PROFILE POPUP -->
                <div class="form-popup" id="edit-profile-form">
                    <form class="form-container" method="post" enctype="multipart/form-data">
						
                        <label>Description</label>
                        <input type="text" name ="user_infor" placeholder="Enter your new description" autocomplete = "off">
                        
                        <label>Profile picture</label>
                        <input type="file" name="image" id="chooseFile"  />   

                        <input type="submit" name="insert" id="insert" value="Update" class="btn btn-info" /> 
                        <button type="button" class="btn cancel" onclick="closeForms()">Close</button>
                    </form>
                </div>
                            
            </ul> <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR--- DONE HERE! --> 

            <ul class="nav navbar-nav navbar-right">
                <li>
                  <a onclick="openEditProfileForm()" id = 'edit_profile_link'>Edit Profile</a>
                </li>
                <li>
                  <a href="logout.php">Logout</a>
                </li>
            </ul>
            <form class="navbar-form navbar-left" role="search" action = "search.php" method = "GET" name = "search_form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search" onkeyup="getLiveSearchUsers(this.value)" name = "q" autocomplete = "off" id="search_text_input"/>
                    <button type="submit" class="btn btn-default">Submit</button>
                        <div class = "results-wrapper">
                            <div class="search_results"></div>
						<div class="search_results_footer_empty"></div>
					</div>
                </div>
            </form>
        </div>
    </nav>
        
    <!-- DISPLAY PROFILE INFORMATION  -->  
    <div class="jumbotron">
        <div class="container" >
            <img id ="profile_img">
                <?php 
                    if($_SESSION['profile_picture']){
                        echo $_SESSION['profile_picture']; 
                    } 
                ?>
                
            </img>
            <div id ="user_infor">
              <p id ="profile-username" ><?php echo $_SESSION['username']; ?></p>
      
              <?php 
                $first_name = $_SESSION['first_name']; 
                $last_name = $_SESSION['last_name']; 
                echo "<p id='profile-name'>$first_name $last_name</p>";
              ?>
      
              <p id ="profile-description" ><?php echo $_SESSION['description']; ?> </p>
            </div>
        </div>

    </div>
    
    <?php
      //----------------------------------------------------------------------
      // DISPLAY POSTS
      //----------------------------------------------------------------------
      $current_user = $_SESSION["username"]; 
      $result = $db -> query("SELECT id 
                              FROM `create` 
                              WHERE username = '$current_user'
                              ORDER BY id DESC"); 

      $current_user_posts = array(); 

      while($row = $result -> fetch_assoc()) {
        array_push($current_user_posts, $row["id"]);  
      }
      
      echo "<div class='container'>
              <div class='row' style='width: 60em'>"; 
      
      foreach ($current_user_posts as $post_id) {
        
        $result = $db -> query("SELECT likes, timestamp, caption, post_image 
                                FROM posts
                                WHERE id = $post_id"); 
        
        $row = $result -> fetch_assoc(); 
          
        $likes = $row["likes"]; 
        $timestamp = date("M j, g:i A", strtotime($row["timestamp"])); 
        $caption = $row["caption"]; 
        $caption = show_tags($caption);
        $post_image = $row["post_image"]; 

        echo "<div class='col-md-4 post'>
        
                <img class='post-image' 
                  src='data:image/jpg;base64,".base64_encode($post_image)."'height='250px' width='250px'/>
                  
                <p class='caption'>$caption</p>
                
                <p class='likes'>$likes 
                  <img src='./assets/images/like_icon.png' alt='like' width='15em'>
                likes</p>
                
                <p class='timestamp'>$timestamp</p>
              </div>";  
      }
      
      echo "  </div>
            </div>"; 
    ?>
  </body>
</html>

<?php function show_tags($str){
  $result = "";
  $words = explode(" ", $str);
  // add <a></a> around tags
  for($i = 0; $i<sizeof($words); $i++){
    $username = $words[$i];
    if($username[0] === '@'){ // if the first letter starts with '@'
      $username = substr($username,1);
      $result = $result . " <a href='profile.php?profile_username=$username'>" . $words[$i] . '</a>';
      continue;
    }
    $result = $result . ' ' . $words[$i];
  }
  return $result;
}?>

