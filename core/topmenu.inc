<?php
//Default topmenu (can be override to display a custom menu)
if(!isset($topmenu)){
$topmenu_selected = [
"text" => "Settings",
"icon" => "glyphicon-wrench"
];
$topmenu[] = [
"text" => "Settings",
"icon" => "glyphicon-wrench",
"link" => "settings.php"
];

$topmenu[] = [
"text" => t("Logout")." (".$currentUser->name().")",
"icon" => "glyphicon-user",
"link" => "index.php?logout"
];

}

$tpl->assign("topmenu_selected",$topmenu_selected);
$tpl->assign("topmenu",$topmenu);
$tpl->draw(CORE_TEMPLATE."topmenu"); //Display core/templates/topmenu.html
?>