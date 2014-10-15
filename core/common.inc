<?php
/**
 * Common loads all functions and global scope variables/constants (user/lang/theme)
 * 
**/
session_name("KANA");
session_start();
require_once("constants.inc"); //Global Constants

/*
LOAD FUNCTIONS
*/
//Add core functionalities
require_once("schema/Entity.class.php"); //SQL manager

include("libraries/Functions.class.php"); //DEPRECATED FUNCTIONALITY @todo remove unused functions and refactor
include("libraries/Draw.class.php"); //Draw Functionnalities
include("libraries/Data.class.php"); //Data
include("libraries/Variable.class.php");
include("schema/Users/Users.class.php"); //User Class
include("schema/Config/Theme.class.php"); //Theme Class
include("libraries/Collector.class.php");
include(EXTERNAL_LIB."Parsedown/Parsedown.php");

include("libraries/Redirect.php"); //Redirection manager

if(DEBUG){
include("libraries/Debug.php"); //Debug 
}
/*

LOAD GLOBAL/SESSION VARIABLES

 */

global $currentUser,$_,$config;

if(DB_EXISTS){
/*
Manage User Security (see schema/Users/User.class.php for more information and index.php for login/logout)
*/
$currentUser = new User();
//Check if an user is saved as a session
if(isset($_SESSION['currentUser'])){
	$currentUser->check_session($_SESSION['currentUser']);
}
else{
//Check if a user is saved as a cookie
	if(isset($_COOKIE[COOKIE_NAME])){
		$currentUser->check_cookie($_COOKIE[COOKIE_NAME]);
		$_SESSION['currentUser'] = serialize($currentUser);
	}
}

/*
Manage General Configuration
*/
//Load configuration (theme/lang/)
if(!isset($_SESSION['config'])){
	$config = new Entity("Config");
	$config = $config->load(0);
	
	$_SESSION["config"] = serialize($config);
}
else{
	$config = unserialize($_SESSION["config"]);
}

//Get Actual Theme if not loaded inside session
if(!isset($_SESSION["theme"])){

	$theme_name = $config["theme"];
	$theme = new Theme($theme_name);
	$_SESSION["theme"] = serialize($theme);
}
else{
	$theme = unserialize($_SESSION["theme"]);
}
}

//Secure GET/POST
$_ = array_map('Functions::secure',array_merge($_POST,$_GET));
//We need the configuration to manage local
include("libraries/Locale.php"); //Load internationalization

?>
