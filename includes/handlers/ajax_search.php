<?php
include("../server.php");
include("../classes/User.php");

$query = $_POST['query'];

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

// if query is not empty, get all users
if($query != ""){
    while($row = mysqli_fetch_array($usersReturnedQuery)){
        echo "<div class='resultDisplay'>
            <a href='profile.php?profile_username=" . $row['username'] . "' style='color: #1485BD'>
                <div class = 'liveSearchProfilePic'>";
                if($row['profile_img'] === null){ // render default pic
                    echo "<img src='assets/images/default_profile.jpeg' alt='s' style='width: 60px;height:60px;border-radius: 50%;object-fit:cover;'/>";
                }else{
                    echo "<img src='data:image/jpeg;base64,".base64_encode($row['profile_img'])."' alt='s' style='width: 60px;height:60px;border-radius: 50%;object-fit:cover;'/>";
                }
                echo "</div>
                <div class = 'liveSearchText'>
                    " . $row['first_name'] . " " . $row['last_name'] . "
                    <p>" . $row['username'] . "</p>
                </div>
            </a>
        </div>";
    }
}
?>