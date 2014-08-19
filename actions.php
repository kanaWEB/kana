<?php
include("core/common.inc");
//if($currentUser->isuser()){
	//@todo check user right to do actions
	if(isset($_["type"])){
		$type = $_["type"];
		include("core/actions/".$type.".actions");
	}
//}
//else
//{
//	echo "NOT LOGGED";
//}
?>