<?php
$leftmenu[] = [
"text" => "System",
"icon" => "glyphicon-info-sign",
"link" => "sys"
];

$leftmenu[] = [
"text" => "Preferences",
"icon" => "glyphicon-cog",
"link" => "pref"
];

$leftmenu[] = [
"text" => "Users",
"icon" => "glyphicon-user",
"link" => "users"
];

$tpl->assign("leftmenu_active",$leftmenu_active);
$tpl->assign("leftmenu",$leftmenu);
$tpl->draw(CORE_TEMPLATE."leftmenu"); //Display core/templates/topmenu.html
?>