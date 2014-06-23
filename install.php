<?php
include("core/common.php"); //Common libraries
include("core/header.php"); //Template (header)

//Include Form functionnalities
include("core/functions/Form.class.php");

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
]
);

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

include("core/footer.php");
?>
