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
            <a class="navbar-brand" href="#">Cosaic</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="index.php" class="current">Home</a>
                </li>
                <li>
                    <a >Create</a>
                </li>

                <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR -->
                <li>
                    <a onclick="openForm()">Profile</a>
                </li>

                <div class="form-popup" id="myForm">
                    <form class="form-container" method="post" enctype="multipart/form-data">
                        <label>Description</label>
                        <input type="text" name ="user_infor" placeholder="Enter your new description" >
                        <label>Profile picture</label>
                        <input type="file" name="image" id="chooseFile"  />   

                        <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-info" /> 
                        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>


                    </form>
                </div>
                            
            </ul> <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR--- DONE HERE! --> 

            <ul class="nav navbar-nav navbar-right">
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

    <div>This is <?php echo $user_array['first_name'] . " " . $user_array['last_name']; ?>'s profile</div>
        

  </body>
</html>