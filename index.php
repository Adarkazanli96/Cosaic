<?php

require_once('server.php');
// redirect to signup if user is not signed up
if (!isset($_SESSION['username'])) {
    header('location: signup.php');
}
?>
<html>

<head>
    <title>Cosaic</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./assets/css/profile.css" > 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Cosaic</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="index.php" class="current">Home</a>
                </li>

                <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR -->
                <li>
                    <a onclick="openForm()">Profile</a>
                </li>



                <div class="form-popup" id="myForm">
                    <form class="form-container" method="post" enctype="multipart/form-data">
                        <label>Description</label>
                        <input type="text" placeholder="Enter your new description" required>
                        <label>Profile picture</label>
                        <input type="file" name="image" id="chooseFile"  />   
                        <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-info" /> 
                        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                    </form>
                </div>
                <!-- CHECK IF INPUT FILE IS A VALID IMAGE -->
                <script>  
                     $(document).ready(function(){  
                          $('#insert').click(function(){  
                               var image_name = $('#image').val();  
                               if(image_name == ''){  
                                    alert("Please Select Image");  
                                    return false;  
                               }else{  
                                    var extension = $('#image').val().split('.').pop().toLowerCase();  
                                    if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1){  
                                         alert('Invalid Image File');  
                                         $('#image').val('');  
                                         return false;  
                                    }  
                               }  
                          });  
                     });  
                </script>
                <!-- OPEN AND CLOSE PROFILE FORM -->
                <script>
                    function openForm() {
                      document.getElementById("myForm").style.display = "block";
                    }

                    function closeForm() {
                      document.getElementById("myForm").style.display = "none";
                    }
                </script>               
            </ul> <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR--- DONE HERE! --> 

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
            <form class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </nav>
        
    <!-- DISPLAY PROFILE INFOR  -->  
    <div class="jumbotron">
        <div class="container" >
            <img id ="profile_img">
                <?php 
                    if($_SESSION['profile_pricture']){
                        echo $_SESSION['profile_pricture']; 
                    } 
                ?>
                
            </img>
            <div id ="user_infor">
                <p id ="profile_content" >WELCOME TO <?php echo $_SESSION['username']; ?> 's page </p>
                <p id ="profile_content" > A little about me: <?php echo $_SESSION['description']; ?> </p>
            </div>
            
        </div>
    </div>
    
    <!-- POSTS OF USERS -->
    <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec id elit non  </p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec id elit non mius. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
      <div class="col-md-4">
        <h2>Heading</h2>
        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulo sit amet risus.</p>
        <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
      </div>
    </div>


</body>
</html>

