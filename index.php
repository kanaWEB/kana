<?php
include("core/common.php"); //Common libraries
//var_dump($currentUser);

//Display login or redirect to install.php/display.php
if(file_exists(DATABASE)){
	//Set inside core/config.php REINSTALL to true to erase database
	if(REINSTALL){unlink(DATABASE);redirect("install");}
	
	include("core/header.php");
	
	if(!$currentUser->right()){
		if(isset($_["submit"])){
			$currentUser = new User();
			$currentUser->check_password($_["username"],$_["pass"]);
			if($currentUser->right()){
			$_SESSION['currentUser'] = serialize($currentUser);
			redirect("display","?notice=You are logged");
			}
			else
			{
			redirect("index","?error=This account doesn't exist");
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
			$login_form->display($tpl);

			include("core/footer.php");
		}
	}
	else
	{
		echo "test";
		redirect("display");
	}


}
else{
	redirect("install");
}
?>
