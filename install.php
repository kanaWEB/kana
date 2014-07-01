<?php
include("core/common.php"); //Common libraries
include("core/header.php"); //Template (header)

$topmenu_selected = [
"text" => "Installation",
"icon" => "glyphicon-asterisk"
];
$topmenu[] = [
"link" => "",
"text" => "Installation",
"icon" => "glyphicon-asterisk"
];
include("core/topmenu.php");


include("core/functions/Form.class.php");
if(!file_exists(DATABASE)){

	if(isset($_["submit"])){
		if(DEBUG){
			debug("PHP","Variables");
			var_dump($_);
		}

		$config_language = $_["lang"];
		$config_theme = "bootstrap";
		$config = new Entity("Config");
		$config->create();
		$config->setLanguage($config_language);
		$config->setTheme($config_theme);
		$config->save();

		//Save First User
		$name = $_["admin_name"];
		$password = $_["admin_pass"];
		$user_db = new Entity("Users");
		$user_db->create();
		$user_db->setName($name);
		$user_db->setLastName("");
		$user_db->setFirstName("");
		$user_db->setEmail("");
		$user_db->setState("1");
		$user_db->setCookie("");
		$user_db->setToken("");
		$user_db->setPassword(sha1(md5($password)));
		$user_db->save();

		redirect("index");
	}

	else{
//Forms
		$install_form = new Form("Installation");
		$install_form->help("Create an admin account");
		$install_form->help("Define your language");

		$install_form->input([
			"type" => "text",
			"id" => "admin_name",
			"name" => "Login",
			"placeholder" => "admin",
			"selected" => "admin"
			]);

		$install_form->input([
			"type" => "password",
			"id" => "admin_pass",
			"name" => "Password",
			]);

		$lang_list = get_langlist();
		$install_form->input([
			"type" => "select",
			"id" => "lang",
			"name" => "Language",
			"options" => $lang_list,
			"selected" => $_SESSION["LANGUAGE"]
			]);

		$install_form->display($tpl);
	}
}
else
{
	redirect("index");
}

include("core/footer.php");
?>
