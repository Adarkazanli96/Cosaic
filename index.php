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
    <link rel="stylesheet" type="text/css" href="./assets/css/profile.css"> 
    <link rel="stylesheet" type="text/css" href="./assets/css/edit_post.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/pure-min.css" integrity="sha384-oAOxQR6DkCoMliIh8yFnu25d7Eq/PHS21PClpwjOTeU2jRSq11vu66rf90/cZr47" crossorigin="anonymous">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
                <div class="form-popup-profile" id="edit-profile-form">
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
                  <a href="logout.php">Logout</a>
                </li>
            </ul>
      
            <!-- SEACH FORM -->
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
      
            <p id ="profile-description" >
                <?php 
                if(isset($_SESSION['description'])){
                    echo $_SESSION['description']; 
                }
                
                ?>
            </p>

            </div>

            <a onclick="openEditProfileForm()" id = 'edit_profile_link' style="display: block;width: 250px;text-align: center;margin-top: 10;">Edit Profile</a>
        </div>

    </div>


    
    <?php
    
    //UPDATE CAPTION
    if (isset($_POST["update-post-caption"]) && !empty($_POST['update-post-caption'] )) {

      $temp = $_POST["test"];
      $new_caption = mysqli_real_escape_string($db, $_POST['update-post']);
      $query1 = "UPDATE posts 
                SET caption = '$new_caption'
                WHERE  id = '$temp'";
      $result = mysqli_query($db, $query1);
      
    }
    //DELETE CAPTION
    if (isset($_POST["delete_post"]) && !empty($_POST['delete_post'])){
      $temp = $_POST["test"];
      $query1 = "DELETE FROM `create`
                 WHERE `id` = '$temp'";
      $result = mysqli_query($db, $query1);
      $query2 = "DELETE FROM `posts`
                 WHERE `id` = '$temp'";
      $result2 = mysqli_query($db, $query2);
      
    }

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
      
      // Iterates through every post the user has posted. 
      foreach ($current_user_posts as $post_id) {

        
        // Retrieves the information of the post. 
        $result = $db -> query("SELECT * FROM posts WHERE id = $post_id"); 
        
        $row = $result -> fetch_assoc(); 
        
        $post_id = $row["id"]; 
        // $likes = $row["likes"]; 
        $timestamp = date("M j, g:i A", strtotime($row["timestamp"])); 
        $caption = $row["caption"]; 
        $caption = show_tags($caption);
        $post_image = $row["post_image"]; 
        
        // Checks if the post has already been liked by the user. 
        $result = mysqli_query($db, "SELECT like_id
                                     FROM `user_add_likes` 
                                     WHERE username = '$current_user'"); 

        $liked = false; 

        while ($row = mysqli_fetch_assoc($result)) {

          $like_id = $row["like_id"];

          $has_like = mysqli_query($db, "SELECT COUNT(*)
                                         FROM `post_has_likes` 
                                         WHERE post_id = '$post_id' AND like_id = $like_id"); 
          if (mysqli_fetch_row($has_like)[0]) {
            $liked = true;
          }
        }

        // Disables the like button if the post has already been liked 
        // by the current user. 
        if ($liked) {
          $disabled = "disabled='disabled'";
        }
        else {
          $disabled = ""; ;
        }
        
        // Retrieves the number of likes the post has. 
        $fetch_likes = mysqli_query($db, "SELECT COUNT(*) FROM `post_has_likes` WHERE post_id = $post_id"); 
        $like_count = mysqli_fetch_row($fetch_likes)[0]; 

        echo "<div class='col-md-4 post'>
        
                <img class='post-image' 
                  src='data:image/jpg;base64,".base64_encode($post_image)."'height='250px' width='250px'/>
                  
                <p class='caption'>$caption</p>
                
                <form method='POST' style='margin-bottom: 0.5em;'>
                  $like_count <input type='submit' value='    likes' class='like-button' name='like-button-$post_id' $disabled/>
                </form>
                
                <p class='timestamp'>$timestamp</p>
                
  
                <button class='fa fa-edit post-button' id = '$post_id'> Edit </button>
                </div>";  
        }
      echo "  </div>
            </div>"; 

       
    ?>
 <!-- POP UP FORM TO MODIFY/DELETE POST  -->  
<div class="form-popup-modify-post" id="save-NewCaption">
    <form id="update-form" class="form-container" method="post" enctype="multipart/form-data">

        <label>Update New Caption</label>
        <input type="text" id="caption" name ="update-post" placeholder="Enter your new caption" autocomplete = "off">
        <input type="submit" name="update-post-caption" id = 'insert-update' value="Update" class="btn btn-info" />
        <input type="hidden" value="" id="hidden-input" name="test"/>
        <input type="submit" name="delete_post" id="insert" value="Delete_Post" class="btn btn-info" />
        <button type="button" class="btn cancel" onclick="closeForms()">Close</button>
    </form>
</div>

<!-- The Post modal -->
<div id="post-modal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <div class="modal-content">
    <img id="img01" style="width: 60%; height: 100%;display: block; float: left;"/>
    <div style="width: 40%; height: 100%;background-color: white; float: right;">
      <form action="/action_page.php">
        Comment: <input type="text" name="new-comment"><br>
        <input type="submit" value="Submit">
      </form>
    <div>
  </div>
</div>


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


<script>
var modal = document.getElementById("post-modal");
var modalImg = document.getElementById("img01");

var elements = document.getElementsByClassName("post-image");

var displayModalWithPic = function(element) {
  modal.style.display = "block";
  modalImg.src = element.target.currentSrc;
};

for (var i = 0; i < elements.length; i++) {
    var element = elements[i]
    element.addEventListener('click', (element) => {displayModalWithPic(element)}, false);
}

//user clicks on (x), close the modal
document.getElementsByClassName("close")[0].onclick = function() {
  modal.style.display = "none";
}
</script>