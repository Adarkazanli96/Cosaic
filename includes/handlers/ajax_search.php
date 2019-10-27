<?php
include("../config.php");
include("../classes/User.php");

$query = $_POST['query'];

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

// if query is not empty, get all users
if($query != ""){
    while($row = mysqli_fetch_array($usersReturnedQuery)){
        echo "<div class='resultDisplay'>
            <a href='profile.php?profile_username=" . $row['username'] . "' style='color: #1485BD'>
                <div class = 'liveSearchProfilePic'>
                    <img src='../../assets/images/icons/default_user_icon.png' alt='s' style='width: 40px;height:auto'/>
                </div>
                <div class = 'liveSearchText'>
                    " . $row['first_name'] . " " . $row['last_name'] . "
                    <p>" . $row['username'] . "</p>
                </div>
            </a>
        </div>";
    }
}
?>