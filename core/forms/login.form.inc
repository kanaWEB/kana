<?php
//If no user is load
if(!$currentUser->isuser()){
	//Header
	include("core/header.inc");

	/*
	 Login Form
	*/

	//Top Menu
	$topmenu_selected = [
	"text" => "Identification",
	"icon" => "glyphicon-credit-card"
	];
	$topmenu[] = [
	"link" => "",
	"text" => "Identification",
	"icon" => "glyphicon-credit-card"
	];
	include("core/topmenu.inc");

	//Form
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

	//Footer
	include("core/footer.inc");
}
else
{
	redirect("display"); //Redirect to display if user valid
}
?>