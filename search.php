<?php

include("includes/header.php");
require_once("includes/config.php"); // get database configurations

if(isset($_GET['q'])){
    $query = $_GET['q'];
}
else{
    $query = "";
}

?>

<div class="main_column column" id="main_column">

    <?php
        if($query===""){
            echo "You must enter something in the search box.";
        }
        else{
            $names = explode(" ", $query);

            // if query contains an underscore, assume user is searching for usernames
            if(strpos($query, '_') !== false){
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' LIMIT 8");
            }
            // if there are two words, assume they are first and last names respectively
            else if(count($names) === 2){
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') LIMIT 8");
            }
            // if query has one word only, search first names or last names
            else{
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') LIMIT 8");
            

            }



// check if results were found
if(mysqli_num_rows($usersReturnedQuery) === 0){
    echo "<p style = 'padding-left: 20px'>We can't find anyone with a " . $type . " like: " . $query . "</p>";
}
else{
    echo "<p style = 'padding-left: 20px'>" . mysqli_num_rows($usersReturnedQuery) . " results were found: <br><br></p>";
}


// display results
while($row = mysqli_fetch_array($usersReturnedQuery)) {
    echo "<div class='search_result' style = 'padding-left: 20px'>
            <div class='result_profile_pic'>
                <a href='profile.php?profile_username=" . $row['username'] . "'>
                <div class = 'liveSearchProfilePic'>
                <img src='../../assets/images/icons/default_user_icon.png' alt='s' style='width: 40px;height:auto'/>
            </div>
                " . $row['first_name'] . " " . $row['last_name'] . "
                <p> " . $row['username'] . "</p>
                </a>
            </div>
    </div>
    <hr>
    ";
} // end while

        }


        
    ?>

</div>