<?php
// Include config file
require_once('server.php');
?>

<html >
  <head>
     
    <link rel="stylesheet" type="text/css" href="./assets/css/loginSignup.css" > 

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
   <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <!------ Include the above in your HEAD tag ---------->

    <!-- All the files that are required -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />


  </head>
<body class="text-center">
    <img src="./assets/images/cosaic.png" alt="cosaic" class = "cosaic">
    <div class="whole-group" >
    <!-- Main Form -->
    <div class="login-form-1">  
        <div class="logo">REGISTER</div>      
        <form id="login-form" class="text-left">
          <div class="main-login-form">

            <div class="login-group">
              <form action = "login.php" method = "post">
                <div class="form-group">
                  <label for="lg_username" class="sr-only">Username</label>
                  <!-- name = name -->
                  <input type="text" class="form-control" name="name" placeholder="username">
                </div>
                <div class="form-group">
                  <label for="lg_password" class="sr-only">Password</label>
                  <!-- pass=  pass -->
                  <input type="password" class="form-control" name="pass" placeholder="password">
                </div>
                <div class="form-group">
                  <label for="lg_password" class="sr-only"> Password</label>
                  <!-- pass=  pass -->
                  <input type="password" class="form-control" name="pass2" placeholder="confirm password">
                </div>
                <div class="form-group">
                  <label for="lg_username" class="sr-only">Your name</label>
                  <input type="text" class="form-control" placeholder="Your name">
                </div>
                <div class="form-group">
                  <label for="lg_username" class="sr-only">Lets Cosaic and friend know a little about yourself!</label>
                  <input type="text" class="form-control" placeholder="description">
                </div>
                <button type="submit" formmethod="post" formaction="server.php" class="login-button" name ="signup"><i class="fa fa-chevron-right"></i>
                </button>
              </form>
            </div>

          </div>
          <div class="etc-login-form">
            <p>already have an account? <a href="login.php">login here</a></p>
          </div>
          
        </form>
      </div> 
    </div>
  </body>

</html>     
          