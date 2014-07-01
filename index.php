<?php
include("core/common.php"); //Common libraries

//Logout
if(isset($_["logout"]) && !isset($_["submit"])){
//Delete Cookie if it exist
	if(isset($_COOKIE[COOKIE_NAME])){
		$user = new Entity("Users");
		$user = $user->load(array("id"=>$currentUser->Id()));
	$user->setCookie(""); //Reset cookie token
	$user->save();
	Functions::destroyCookie(COOKIE_NAME);
}
$_SESSION = array();
session_unset();
session_destroy();
$currentUser = new User();

}

//Display login or redirect to install.php/display.php
if(file_exists(DATABASE)){
	//Set inside core/config.php REINSTALL to true to erase database
	if(REINSTALL){unlink(DATABASE);redirect("install");}
	
	include("core/header.php");
	
	if(!$currentUser->isuser()){
		if(isset($_["submit"])){
			//Check user
			$currentUser = new User();
			$cookie = isset($_["cookie"]);
			$currentUser->check_password($_["username"],$_["pass"],$cookie);

			if($currentUser->isuser()){
				$_SESSION['currentUser'] = serialize($currentUser);
				redirect("display","?notice=".t("You are logged"));
			}
			else{
				redirect("index","?error=".t("This account doesn't exist"));
			}
		}
		else{
			$topmenu_selected = [
			"text" => "Identification",
			"icon" => "glyphicon-credit-card"
			];
			$topmenu[] = [
			"link" => "",
			"text" => "Identification",
			"icon" => "glyphicon-credit-card"
			];
			include("core/topmenu.php");

			include("core/functions/Form.class.php");
			$login_form = new Form("Identification");
			$login_form->help("You need to be logged to use this application");
			$login_form->input([
				"type" => "text",
				"id" => "username",
				"name" => "Login",
				"placeholder" => "admin",
				"selected" => "admin"
				]);

			$login_form->input([
				"type" => "password",
				"id" => "pass",
				"name" => "Password",
				]);

			$login_form->input([
				"type" => "checkbox",
				"id" => "cookie",
				"name" => "Remember me",
				"selected" => false
				]);

			$login_form->display($tpl);

			include("core/footer.php");
		}
	}
	else
	{
		redirect("display");
	}


}
else{
	redirect("install");
}
?>
