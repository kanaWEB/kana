<?php
$preferences_form = new Form("Preferences");


$lang_list = get_langlist();

$theme_list = new Theme();
$theme_list = $theme_list->getlist();

$preferences_form->input([
	"type" => "select",
	"id" => "theme",
	"name" => "Theme",
	"options" => $theme_list,
	"selected" => $config->getTheme()
	]);

$preferences_form->input([
	"type" => "select",
	"id" => "lang",
	"name" => "Language",
	"options" => $lang_list,
	"selected" => $_SESSION["LANGUAGE"]
	]);

$preferences_form->display($tpl);
?>