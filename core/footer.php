<?php

//Manage Error Message
if(isset($_["error"])){
	$error = t($_["error"]);
}
else
{
	$error = false;
}

if(isset($_["notice"])){
	$notice = t($_["notice"]);
}
else
{
	$notice = false;
}

$tpl->assign("error_message",$error);
$tpl->assign("notice_message",$notice);
$tpl->draw(USER_TEMPLATE."bottombar");
$tpl->draw(CORE_TEMPLATE."footer");
?>

