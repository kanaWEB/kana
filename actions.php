<?php
include("core/common.inc");
if($currentUser->isuser() || !isset($_SERVER['REMOTE_ADDR'])){
	//@todo check user right to do actions
	if(isset($_["type"])){
		$type = $_["type"];
		include("core/actions/".$type.".actions");
	}
}
else
{
	echo Draw::ajax_notify("NOT LOGGED","error");
}
?>