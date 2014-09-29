<?php
include("core/common.inc");

if(isset($_["token"])){
	$currentUser = new User;
	$currentUser->check_token($_["token"]);
	if(!$currentUser->id()){
		$affirmation = "Token invalid";
							$response = array('responses'=>array(
								array('type'=>'talk','sentence'=>$affirmation)
								)
							);

							$json = json_encode($response);
							echo $json;
		exit();
	}
}

if($currentUser->isuser() || !isset($_SERVER['REMOTE_ADDR'])){
	//@todo check user right to do actions
	if(isset($_["type"])){
		$type = $_["type"];
		include("core/actions/".$type.".actions");
	}
}
else
{
	$affirmation = "No token set";
							$response = array('responses'=>array(
								array('type'=>'talk','sentence'=>$affirmation)
								)
							);

							$json = json_encode($response);
							echo $json;
	//echo Draw::ajax_notify("NOT LOGGED","error");
}
?>