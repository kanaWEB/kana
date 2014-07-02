<?php
$users_form = new Form("Users");

$preferences_form->input([
	"type" => "select",
	"id" => "theme",
	"name" => "Theme",
	"options" => $theme_list,
	"selected" => $config->getTheme()
	]);


$users_form->display($tpl);


//Footer
include("core/footer.inc");
//}
?>