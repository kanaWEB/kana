<?php
session_start();
include("config.php");

//Add core functionalities
include("functions/SQLiteEntity.class.php");
include("functions/Locale.php");
include("functions/Functions.class.php"); //DEPRECATED FUNCTIONALITY
include("functions/Redirect.class.php");
include("functions/Debug.php");
include("functions/Users.class.php");

global $currentUser,$_;

//Secure GET/POST
$_ = array_map('Functions::secure',array_merge($_POST,$_GET));


$currentUser = new User();
if(isset($_SESSION['currentUser'])){
$currentUser->check_session($_SESSION['currentUser']);
}
//if(!$currentUser && isset($_COOKIE[$conf->get("COOKIE_NAME")])){
//	$users = User::getAllUsers();
//	foreach ($users as $user) {
//		if($user->getCookie() == $_COOKIE[$conf->get("COOKIE_NAME")]) 
//		{
//			if (DEBUG) error_log("Cookie granted users right");
//			$myUser = $user;
//		}
//	}
//}
?>
