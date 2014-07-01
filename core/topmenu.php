<?php

if(!isset($topmenu)){
$topmenu_selected = [
"text" => "Settings",
"icon" => "glyphicon-wrench"
];
$topmenu[] = [
"text" => "Settings",
"icon" => "glyphicon-wrench",
"link" => "setting.php"
];

$topmenu[] = [
"text" => $currentUser->name(),
"icon" => "glyphicon-user",
"link" => "index.php"
];

}

$tpl->assign("topmenu_selected",$topmenu_selected);
$tpl->assign("topmenu",$topmenu);
$tpl->draw(USER_TEMPLATE."topmenu");
?>