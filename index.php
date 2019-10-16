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
                <form action="index.php" class="form-container" method = "post">

                    <label><b>Description</b></label>
                    <input type="text" placeholder="Enter your new description" required>
                    <label for="email"><b>Profile picture</b></label>


                    <div class="container" style="width:500px;">  
                        <br />  
                        <form method="post" enctype="multipart/form-data">  
                             <input type="file" name="image" id="image" /> 
                        </form>  
                    </div>

                    <button type="submit" formmethod="post" formaction="server.php" class="btn btn-info" name="insert" >Save</button>
                    
                    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>

                </form>
                </div>

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

            <h2 class="display-3">WELCOME TO <?php echo $_SESSION['username']; ?> 's page </h2>
            <p> A little about me: <?php echo $_SESSION['description']; ?> </p>
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

