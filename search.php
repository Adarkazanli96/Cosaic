<?php
require_once("includes/server.php"); // get database configurations

if(isset($_GET['q'])){
    $query = $_GET['q'];
}
else{
    $query = "";
}

?>

<html>

<head>
    <title>Cosaic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="./assets/images/cosaic_favicon.png">
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/profile.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="main.js" type="text/javascript"></script>
</head>

<body>

    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <img href="index.php" src='./assets/images/cosaic_navbar_logo.png' alt='like' height="40em">
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="index.php" class="current">Home</a>
                </li>
                <li>
                    <a>Create</a>
                </li>

                <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR -->
                <li>
                    <a onclick="openForm()">Profile</a>
                </li>



                <div class="form-popup" id="myForm">
                    <form class="form-container" method="post" enctype="multipart/form-data">
                        <label>Description</label>
                        <input type="text" name="user_infor" placeholder="Enter your new description">
                        <label>Profile picture</label>
                        <input type="file" name="image" id="chooseFile" />

                        <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-info" />
                        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>


                    </form>
                </div>

            </ul> <!-- POPUP/UPDATE PROFILE FORM ON NAV BAR--- DONE HERE! -->

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="logout.php">Logout</a>
                </li>f
            </ul>
            <form class="navbar-form navbar-left" role="search" action="search.php" method="GET" name="search_form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search"
                        onkeyup="getLiveSearchUsers(this.value)" name="q" autocomplete="off" id="search_text_input" />
                    <button type="submit" class="btn btn-default">Submit</button>
                    <div class="results-wrapper">
                        <div class="search_results"></div>
                        <div class="search_results_footer_empty"></div>
                    </div>
                </div>
            </form>
        </div>
    </nav>


    <div class="main_column column" id="main_column">

        <?php
        if($query===""){
            echo "<p style = 'padding-left: 20px'>You must enter something in the search box.</p>";
        }
        else{
            $names = explode(" ", $query);
            $username = $_SESSION['username'];

            // if query contains an underscore, assume user is searching for usernames
            if(strpos($query, '_') !== false){
                $usersReturnedQuery = mysqli_query($db, "SELECT * FROM users WHERE username LIKE '$query%' AND username <> '$username' LIMIT 8");
            }
            // if there are two words, assume they are first and last names respectively
            else if(count($names) === 2){
                $usersReturnedQuery = mysqli_query($db, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND username <> '$username' LIMIT 8");
            }
            // if query has one word only, search first names or last names
            else{
                $usersReturnedQuery = mysqli_query($db, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND username <> '$username' LIMIT 8");
            }


            // check if results were found
            if(mysqli_num_rows($usersReturnedQuery) === 0){
                echo "<p style = 'padding-left: 20px'>We can't find anyone with the name like: <span style='font-style: italic'>" . $query . "</span></p>";
            }
            else{
            echo "<p style = 'padding-left: 20px'>" . mysqli_num_rows($usersReturnedQuery) . " results were found: <br><br></p>";
            }


            // display results
            while($row = mysqli_fetch_array($usersReturnedQuery)) {
            echo "<div class='search_result' style = 'padding-left: 20px'>
                <div class='result_profile_pic'>
                    <a href='profile.php?profile_username=" . $row['username'] . "'>
                        <div class = 'liveSearchProfilePic'>";
                        if($row['profile_img'] === null){ // render default pic
                            echo "<img src='assets/images/default_profile.jpeg' alt='s' style='width: 60px;height:60px;border-radius: 50%;'/>";
                        }else{
                            echo "<img src='data:image/jpeg;base64,".base64_encode($row['profile_img'])."' alt='s' style='width: 60px;height:60px;border-radius: 50%;'/>";
                        }
                        echo "</div>
                        " . $row['first_name'] . " " . $row['last_name'] . "
                        <p> " . $row['username'] . "</p>
                    </a>
                </div>
            </div>
            <hr>";
        }
    }
    ?>

    </div>

</body>

</html>