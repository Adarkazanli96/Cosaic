<?php
session_start();

// redirect to signup if user is not signed up
if (!isset($_SESSION['username'])) {
    header('location: signup.php');
}

require_once ('includes/server.php');

if(isset($_GET['profile_username'])){
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
    $user_array = mysqli_fetch_array($user_details_query);
}
?>

<html>

<head>
    <title>Cosaic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="./assets/images/cosaic_favicon.png">
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" > 
    <link rel="stylesheet" type="text/css" href="./assets/css/profile.css" > 
    <link rel="stylesheet" type="text/css" href="./assets/css/modal.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="main.js" type="text/javascript">></script>
</head>

<body>

<nav class="navbar" role="navigation">
        <div class="navbar-header">
            <a href="index.php"><img src='./assets/images/cosaic_navbar_logo.png' alt='like' height="40em"></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
            
            </ul> <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR--- DONE HERE! --> 

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
      
            <!-- LOGOUT BUTTON-->  
            <ul class="btn btn-default logout-button">
              <a href="logout.php" class="logout-button-text">Logout</a>
            </ul>
        </div>
    </nav>

<?php if($user_array['username'] === null){?>
   <p style = 'text-align: center'> User does not exist</p>
<?php }else {?>


    <!-- DISPLAY PROFILE INFOR  -->  
    <div class="jumbotron">
        <div class="container" >
            <img id ="profile_img">
                <?php 
                    if($user_array['profile_img'] === null){ // if no profile pic in database
                        echo '<img src="assets/images/default_profile.jpeg" height="250" width="250" style = "object-fit: cover;" />';
                    }else{
                        echo '<img src="data:image/jpeg;base64,'.base64_encode($user_array['profile_img']).'" height="250" width="250" style = "object-fit: cover;"/>';
                    }
                ?>

            </img>
            <div id ="user_infor">
              <p id ="profile-username" ><?php echo $user_array['username'];?></p>
              <p id='profile-name'><?php echo $user_array['first_name'] . " " . $user_array['last_name'];?></p> 
              <p id ="profile-description" ><?php echo $user_array['description']; ?></p>
            </div>
        </div>
    </div>  
      
  </body>
</html>

<?php } ?>

<?php
      //----------------------------------------------------------------------
      // DISPLAY POSTS
      //----------------------------------------------------------------------
      $current_user = $user_array['username']; 

      // get user information for modal
      $result = $db -> query("SELECT profile_img FROM `users` WHERE username = '$current_user'");
      $profile_pic = $result -> fetch_assoc();
      $profile_pic = $profile_pic['profile_img'];

      //get post information for displaying posts
      $result = $db -> query("SELECT id 
                              FROM `create` 
                              WHERE username = '$current_user'
                              ORDER BY id DESC"); 

      $current_user_posts = array(); 

      while($row = $result -> fetch_assoc()) {
        array_push($current_user_posts, $row["id"]);  
      }
      
      echo "<div class='container align-center' style='padding-left: 10%'>
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
        $caption_with_tags = show_tags($caption);
        $post_image = $row["post_image"];
        
        // Checks if the post has already been liked by the user. 
        $result = mysqli_query($db, "SELECT like_id
                                     FROM `user_add_likes` 
                                     WHERE username = '".$_SESSION['username']."' "); 

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
          $like_button_class = "like-button-disabled"; 
        }
        else {
          $disabled = ""; 
          $like_button_class = "like-button"; 
        }
        
        // Retrieves the number of likes the post has. 
        $fetch_likes = mysqli_query($db, "SELECT COUNT(*) FROM `post_has_likes` WHERE post_id = $post_id"); 
        $like_count = mysqli_fetch_row($fetch_likes)[0]; 

        echo "<div class='col-md-4 post'>
        
                <img class='post-image'
                  onclick='showModal(`" . base64_encode($post_image) . "`, `" . $caption . "`, `" . base64_encode($profile_pic) . "`, `" . $current_user . "`, `" . $post_id . "`)'
                  src='data:image/jpg;base64,".base64_encode($post_image)."'height='250px' width='250px'
                  style = 'object-fit: cover;'/>
                  
                <p class='caption'>$caption_with_tags</p>
                
                <form method='POST' style='margin-bottom: 0.5em;'>
                  $like_count <input type='submit' value='    likes' class='$like_button_class' name='like-button-$post_id' $disabled/>
                </form>
                
                <p class='timestamp'>$timestamp</p>
              </div>";  
      }
      
      echo "  </div>
            </div>"; 
    ?>


    <!-- The modal for selecting a post -->
<div id="modal" class="modal"  >

<!-- The Close Button -->
<span class="close">&times;</span>

<!-- Modal Content -->
<div class="modal-content">
  <div style = "text-align: center !important;float: left;width: 60%;height: 100%">
    <img class="img-responsive" id="modal-picture"/>
  </div>
  <div class = "modal-description">
    <div style = 'overflow-y: auto; height: 90%'>
      <div style = "margin-top: 15px; margin-left: 15px;">
        <img id = 'modal-profile-pic' alt='s'/>
        <div style = 'position: relative; left: 15px; float: left; width: 200px;word-wrap: break-word;'>
          <strong id = "modal-username"></strong>
          <div id='modal-caption'></div>
        </div>
        <div style = 'clear: both;'></div>
      </div>
      <br> 
      <hr/>
      <div id="comment-thread"></div>
    </div>
    <form id = 'comment-form'>
      <div style="border-top: 1px solid lightgrey">
        <input id = 'comment-content' type="text" name ="comment-content" placeholder="Add a comment...">
        <input type="hidden" value="" id="hidden-input-post-id" name="hidden-input-post-id"/>
        <button type="submit" formmethod="post" name ="post-comment">Post</button>
      </div>
    </form>
  </div>
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
function showModal(post_image, caption, profile_pic, username, post_id){
    var modal = document.getElementById('modal');
    var modalImg = document.getElementById('modal-picture');
    var modalCaption = document.getElementById('modal-caption')
    var profilePicture = document.getElementById('modal-profile-pic')
    var modalUsername = document.getElementById('modal-username')

    modal.style.display = 'block';
    modalImg.src = `data:image/jpg;base64, ${post_image}`;
    if(profile_pic === "") profilePicture.src = "assets/images/default_profile.jpeg"
    else{
      profilePicture.src = `data:image/jpg;base64, ${profile_pic}`;
    }
    modalCaption.innerHTML = caption
    modalUsername.innerHTML = username;

    // AJAX call to get all comments of post
    getComments(post_id);

    $('#hidden-input-post-id').val(post_id) // set the hidden post id in the form

    //user clicks on (x), close the modal
    document.getElementsByClassName('close')[0].onclick = function() {
      modalImg.src = ''
      modalCaption.innerHTML = ''
      modal.style.display = 'none';
    }
}

</script>