<?php
session_start();
include("../settings/connect_datebase.php");
include("../settings/jwt.php");

$login = $_POST['login'];
$password = $_POST['password'];

$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
$user = $query_user->fetch_row();

if($user) {
    $id = $user[0];
    $role = $user[3];
    $token = generate_jwt_habr($id, $role);
    
    setcookie("access_token", $token, time() + 3600, "/");
    $_SESSION['user'] = $id; 
    
    echo $token; 
} else {
    echo ""; 
}
?>