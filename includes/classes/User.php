<?php 
// contains the class for getting all info about a user

class User {
    private $user;
    private $db;

    public function __construct($db, $user){
        $this->db = $db;
        $user_details_query = mysqli_query($db, "SELECT * FROM users WHERE username='$user'");
        $this->user = mysqli_fetch_array($user_details_query);
    }

    public function getFirstAndLastName(){
        $username = $this->user['username'];
        $query = mysqli_query($this->db, "SELECT first_name, last_name FROM users WHERE username='$username'");
        $row = mysqli_fetch_array($query);
        return $row['first_name'] . " " . $row['last_name'];
    }
}

?>