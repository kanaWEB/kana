<?php
include("core/common.inc");

if(isset($_["token"])){
	$currentUser = new User;
	$currentUser->check_token($_["token"]);
	if(!$currentUser->id()){
		$affirmation = "Token invalid";
		
		$tokenlog_db = new Entity("core","TokenLog");
		$ip_selected = $tokenlog_db->load([
			"ipaddress" => $_SERVER['REMOTE_ADDR'],
			"token" => $_["token"]
		]);

		//If there was already a request from this ip increment it
			if($ip_selected){
				$nbrequest = $ip_selected["nbrequest"] + 1;
				$tokenlog_db->change([
					"nbrequest" => $nbrequest,
					"timestamp" => time()
					], 
					['id'=>$ip_selected["id"]]
					);

			}
			//If this is the first request then save it
			else{
				$tokenlog_db->SetIpaddress($_SERVER['REMOTE_ADDR']);
				$tokenlog_db->SetTimeStamp(time());
				$tokenlog_db->SetToken($_["token"]);
				$tokenlog_db->SetNbrequest(1);
				$tokenlog_db->Save();
			}
			echo '{"error":"invalid or missing token"}'; //Ouput permission error message

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
		$affirmation = "No token set / Login Failed";
		$response = array('responses'=>array(
			array('type'=>'talk','sentence'=>$affirmation)
			)
		);

		$json = json_encode($response);
		echo $json;
	//echo Draw::ajax_notify("NOT LOGGED","error");
	}
	?>